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
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReceiveController extends Controller
{
    public function index()
    {
        // Tampilkan daftar penerimaan ATK
    }

    public function create(PurchaseOrder $purchaseOrder)
    {
        // Eager load relationships
        $purchaseOrder->load(['items', 'supplier']);
        
        // Get active suppliers for dropdown
        $suppliers = Supplier::where('status', 'active')
            ->pluck('name', 'id');
        
        // Get ATK items with unit information
        $atkItems = Item::select('id', 'name', 'unit')->get();
        
        return view('atk.receives.create', compact(
            'purchaseOrder',
            'suppliers',
            'atkItems'
        ));
    }

    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $this->validateReceiveRequest($request, $purchaseOrder);

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

            return redirect()
                ->route('atk.purchase-orders.index')
                ->with('success', 'Items successfully received.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Receive failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to receive items: ' . $e->getMessage()]);
        }
    }

    private function validateReceiveRequest(Request $request, PurchaseOrder $purchaseOrder): array
    {
        // Step 1: Validasi struktur data
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.atk_purchase_order_item_id' => 'required|exists:atk_purchase_order_items,id',
            'items.*.atk_item_id' => 'required|exists:atk_items,id',
            'items.*.qty' => ['nullable', 'numeric', 'min:0'],
            'receive_note' => 'nullable|string',
            'receipt_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Step 2: Ambil data PO Items untuk validasi sisa qty
        $poItemIds = collect($validated['items'])->pluck('atk_purchase_order_item_id');
        $poItems = PurchaseOrderItem::whereIn('id', $poItemIds)->get()->keyBy('id');

        // Step 3: Normalisasi qty dan validasi tidak melebihi remaining
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

        Stock::create([
            'atk_item_id' => $atkItem->id,
            'type' => 'in',
            'qty' => $itemData['qty'],
            'note' => "Receive from PO #{$receive->purchaseOrder->po_number}",
        ]);

        $atkItem->increment('current_stock', $itemData['qty']);
    }
}
