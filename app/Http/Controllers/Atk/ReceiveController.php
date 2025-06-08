<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use App\Models\Atk\PurchaseOrder;
use App\Models\Atk\PurchaseOrderItem;
use App\Models\Atk\Receive;
use App\Models\Atk\ReceiveItem;
use App\Models\Atk\Stock;
use App\Models\Atk\Item;
use App\Models\Master\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReceiveController extends Controller
{
    public function getReceives(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $receives = Receive::with(['purchaseOrder', 'receiver.employee', 'items'])->get();

        $data = $receives->map(function ($receive) {
            return [
                'id' => $receive->id,
                'po_number' => $receive->purchaseOrder->po_number ?? '-',
                'receive_date' => \Carbon\Carbon::parse($receive->receive_date)?->format('Y-m-d'),
                'receiver_name' => optional($receive->receiver?->employee)->full_name ?? '-',
                'qty_total' => $receive->items->sum('qty'),
                'note' => $receive->note ?? '-',
                'receipt_file' => $receive->receipt_file
                    ? asset('storage/' . $receive->receipt_file)
                    : null,
            ];
        });

        return response()->json($data);
    }

    public function index()
    {
        return view('atk.receives.index');
    }

    public function show(Receive $receive)
    {
        $receive->load([
            'items.item',
            'receiver.employee',
            'purchaseOrder'
        ]);

        return view('atk.receives.show', compact('receive'));
    }

    public function create(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['items', 'supplier']);

        $suppliers = Supplier::active()->pluck('name', 'id');
        $atkItems = Item::select('id', 'name', 'unit')->get();

        return view('atk.receives.create', compact('purchaseOrder', 'suppliers', 'atkItems'));
    }

    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $this->validateReceiveRequest($request);

        DB::beginTransaction();

        try {
            $purchaseOrder->load('items');
            [$poItems, $atkItems] = $this->getPOAndAtkItems($validated['items']);
            $receiptPath = $this->storeReceiptFile($request);

            $receive = Receive::create([
                'atk_purchase_order_id' => $purchaseOrder->id,
                'received_by' => auth()->id(),
                'receive_date' => now(),
                'note' => $validated['receive_note'] ?? null,
                'receipt_file' => $receiptPath,
            ]);

            foreach ($validated['items'] as $index => $itemData) {
                if (($itemData['qty'] ?? 0) > 0) {
                    $this->processReceiveItem($itemData, $receive, $poItems, $atkItems, $index);
                }
            }

            $purchaseOrder->load('items');
            $status = $purchaseOrder->items->every(fn ($item) => $item->received_qty >= $item->qty)
                ? 'received' : 'partial';
            $purchaseOrder->update(['status' => $status]);

            DB::commit();

            return redirect()->route('atk.purchase-orders.index')->with('success', 'Items successfully received.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Receive failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to receive items: ' . $e->getMessage()]);
        }
    }

    private function validateReceiveRequest(Request $request): array
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.atk_purchase_order_item_id' => 'required|exists:atk_purchase_order_items,id',
            'items.*.atk_item_id' => 'required|exists:atk_items,id',
            'items.*.qty' => ['nullable', 'numeric', 'min:0'],
            'receive_note' => 'nullable|string',
            'receipt_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $poItemIds = collect($validated['items'])->pluck('atk_purchase_order_item_id');
        $poItems = PurchaseOrderItem::whereIn('id', $poItemIds)->get()->keyBy('id');

        $hasQty = false;

        $validated['items'] = collect($validated['items'])->map(function ($item) use (&$hasQty, $poItems) {
            $poItem = $poItems[$item['atk_purchase_order_item_id']] ?? null;
            $qty = isset($item['qty']) && is_numeric($item['qty']) ? (int) $item['qty'] : 0;
            $remainingQty = $poItem ? max(0, $poItem->qty - $poItem->received_qty) : 0;

            if ($qty > 0) {
                $hasQty = true;
            }

            if ($qty > $remainingQty) {
                throw ValidationException::withMessages([
                    "items.{$item['atk_purchase_order_item_id']}.qty" => "Quantity exceeds remaining quantity of {$remainingQty}."
                ]);
            }

            $item['qty'] = $qty;
            return $item;
        })->toArray();

        if (! $hasQty) {
            throw ValidationException::withMessages([
                'items' => ['At least one item must have a quantity greater than 0.']
            ]);
        }

        return $validated;
    }

    private function storeReceiptFile(Request $request): ?string
    {
        return $request->hasFile('receipt_file')
            ? $request->file('receipt_file')->store('receipts', 'public')
            : null;
    }

    private function getPOAndAtkItems(array $items): array
    {
        $poItemIds = collect($items)->pluck('atk_purchase_order_item_id');
        $itemIds = collect($items)->pluck('atk_item_id');

        $poItems = PurchaseOrderItem::whereIn('id', $poItemIds)->get()->keyBy('id');
        $atkItems = Item::whereIn('id', $itemIds)->get()->keyBy('id');

        return [$poItems, $atkItems];
    }

    private function processReceiveItem(array $itemData, Receive $receive, $poItems, $atkItems, int $index): void
    {
        $poItem = $poItems[$itemData['atk_purchase_order_item_id']] ?? null;
        $atkItem = $atkItems[$itemData['atk_item_id']] ?? null;

        if (!$poItem || !$atkItem) {
            throw ValidationException::withMessages([
                "items.{$index}" => "Invalid item data.",
            ]);
        }

        ReceiveItem::create([
            'atk_receive_id' => $receive->id,
            'atk_purchase_order_item_id' => $poItem->id,
            'atk_item_id' => $atkItem->id,
            'qty' => $itemData['qty'],
        ]);

        $poItem->increment('received_qty', $itemData['qty']);

        $beginingStock = $atkItem->current_stock;
        $endingStock = $beginingStock + $itemData['qty'];

        Stock::create([
            'atk_item_id' => $atkItem->id,
            'type' => 'in',
            'qty' => $itemData['qty'],
            'note' => "Receive from PO #{$receive->purchaseOrder->po_number}",
            'begining_stock' => $beginingStock,
            'ending_stock' => $endingStock,
        ]);

        $atkItem->increment('current_stock', $itemData['qty']);
    }
}
