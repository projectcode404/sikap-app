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
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PurchaseOrderController extends Controller
{
    public function getPurchaseOrders(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $purchase_orders = PurchaseOrder::with([
            'supplier:id,name',
            'user.employee:id,full_name'
        ])->get();

        // Optionally format the output
        $data = $purchase_orders->map(function ($po) {
            return [
                'id' => $po->id,
                'po_number' => $po->po_number,
                'po_date' => Carbon::parse($po->po_date)->translatedFormat('d F Y'), // e.g. 08 June 2025
                'schedule_date' => Carbon::parse($po->schedule_date)->translatedFormat('d F Y'), // e.g. 08 June 2025
                'supplier_name' => $po->supplier->name ?? null,
                'created_by' => $po->user->employee->full_name ?? null,
                'status' => $po->status,
            ];
        });

        return response()->json($data);
    }

    public function index()
    {
        return view('atk.purchase-orders.index');
    }

    public function submitGr(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        if (!$request->isMethod('post')) {
            return abort(404); // atau 405 Method Not Allowed
        }

        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:atk_purchase_orders,id',
            'gr_number_sap' => 'required|string|max:50|unique:atk_purchase_orders,receipt_number',
        ]);

        $po = PurchaseOrder::findOrFail($validated['purchase_order_id']);

        if ($po->status !== 'received') {
            return response()->json(['message' => 'PO status is not valid for GR.'], 422);
        }

        $po->update([
            'receipt_number' => $validated['gr_number_sap'],
            'status' => 'completed',
        ]);

        return response()->json(['message' => 'GR submitted successfully!'], 200);
    }

    public function create()
    {
        $atkItems = Item::all();
        $suppliers = Supplier::where('status', 'active')->pluck('name', 'id');
        return view('atk.purchase-orders.create', compact('atkItems', 'suppliers'));
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        // Eager load relationships
        $purchaseOrder->load(['items', 'supplier', 'receives', 'receives.receiveItems', 'receives.receiver']);

        // Get active suppliers for dropdown
        $suppliers = Supplier::where('status', 'active');
        
        // Get ATK items with unit information
        $atkItems = Item::select('id', 'name', 'unit')->get();

        $receives = Receive::where('atk_purchase_order_id', $purchaseOrder->id)
            ->with(['receiveItems'])
            ->first();
        
        return view('atk.purchase-orders.show', compact(
            'purchaseOrder',
            'suppliers',
            'atkItems',
            'receives',
        ));
    }

    protected function validatePurchaseOrder(Request $request, ?PurchaseOrder $purchaseOrder = null): array
    {
        $poId = $purchaseOrder?->id;

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'po_number' => [
                'required',
                'string',
                'max:50',
                $poId
                    ? 'unique:atk_purchase_orders,po_number,' . $poId
                    : 'unique:atk_purchase_orders,po_number',
            ],
            'po_date' => 'required|date',
            'schedule_date' => 'nullable|date',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.atk_item_id' => 'required|exists:atk_items,id',
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        return $validated;
    }

    public function store(Request $request)
    {
        $validated = $this->validatePurchaseOrder($request);

        try {
            DB::beginTransaction();

            $po = PurchaseOrder::create([
                'supplier_id' => $validated['supplier_id'],
                'po_number' => $validated['po_number'],
                'po_date' => $validated['po_date'],
                'schedule_date' => $validated['schedule_date'] ?? null,
                'note' => $validated['note'],
                'status' => 'open',
                'created_by' => auth()->id(),
            ]);

            $itemUnits = Item::whereIn('id', collect($validated['items'])->pluck('atk_item_id'))->pluck('unit', 'id');

            $items = collect($validated['items'])->map(fn ($item) => [
                'atk_item_id' => $item['atk_item_id'],
                'qty' => $item['qty'],
                'unit' => $itemUnits[$item['atk_item_id']] ?? null,
            ])->toArray();

            $po->items()->createMany($items);

            DB::commit();

            return redirect()->route('atk.purchase-orders.index')->with('success', 'Purchase Order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create Purchase Order: ' . $e->getMessage());
        }
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'open') {
            return redirect()
                ->route('atk.purchase-orders.index')
                ->with('error', 'Cannot edit Purchase Order with status ' . $purchaseOrder->status);
        }
        
        // Eager load relationships
        $purchaseOrder->load(['items', 'supplier']);
        
        // Get active suppliers for dropdown
        $suppliers = Supplier::where('status', 'active')
            ->pluck('name', 'id');
        
        // Get ATK items with unit information
        $atkItems = Item::select('id', 'name', 'unit')->get();
        
        return view('atk.purchase-orders.create', compact(
            'purchaseOrder',
            'suppliers',
            'atkItems'
        ));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $this->validatePurchaseOrder($request, $purchaseOrder);

        try {
            DB::beginTransaction();

            if ($purchaseOrder->status !== 'open') {
                return back()->with('error', 'Cannot update Purchase Order with status ' . $purchaseOrder->status);
            }

            $purchaseOrder->update([
                'supplier_id' => $validated['supplier_id'],
                'po_number' => $validated['po_number'],
                'po_date' => $validated['po_date'],
                'schedule_date' => $validated['schedule_date'] ?? null,
                'note' => $validated['note'],
            ]);

            $itemUnits = Item::whereIn('id', collect($validated['items'])->pluck('atk_item_id'))->pluck('unit', 'id');

            $updatedItemIds = collect($validated['items'])->pluck('atk_item_id');
            $purchaseOrder->items()->whereNotIn('atk_item_id', $updatedItemIds)->delete();

            foreach ($validated['items'] as $item) {
                $purchaseOrder->items()->updateOrCreate(
                    ['atk_item_id' => $item['atk_item_id']],
                    [
                        'qty' => $item['qty'],
                        'unit' => $itemUnits[$item['atk_item_id']] ?? 'pcs'
                    ]
                );
            }

            DB::commit();
            return redirect()->route('atk.purchase-orders.index')->with('success', 'Successfully updated Purchase Order!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update Purchase Order: ' . $e->getMessage());
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        try {
            DB::transaction(function () use ($purchaseOrder) {
                // Authorization check (sesuaikan dengan policy Anda)
                // $this->authorize('delete', $purchaseOrder);
                
                // Cek status PO
                if ($purchaseOrder->status !== 'open') {
                    throw new \Exception('Can\'t delete Purchase Order with status ' . $purchaseOrder->status);
                }

                // Cek relasi penerimaan barang
                if ($purchaseOrder->receives()->exists()) {
                    throw new \Exception('Can\'t delete Purchase Order with existing goods receipt');
                }

                // Hapus semua item PO
                $purchaseOrder->items()->delete();
                
                // Hapus PO
                $purchaseOrder->delete();
            });

            if (request()->ajax() || request()->expectsJson()) {
                return response()->json(['message' => 'Purchase Order #' . $purchaseOrder->po_number . ' successfully deleted!'], 200);
            }

            return redirect()->route('atk.purchase-orders.index')
                ->with('success', 'Purchase Order #' . $purchaseOrder->po_number . ' successfully deleted!');

        } catch (\Exception $e) {
            return redirect()->route('atk.purchase-orders.index')->with('error', 'Failed to delete Purchase Order: ' . $e->getMessage());
        }
    }
}
