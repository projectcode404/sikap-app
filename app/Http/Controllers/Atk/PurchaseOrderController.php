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
                'po_date' => $po->po_date,
                'schedule_date' => $po->schedule_date,
                'supplier_name' => $po->supplier->name ?? null,
                'created_by' => $po->user->employee->full_name ?? null,
                'status' => $po->status,
            ];
        });

        return response()->json($data);
    }

    public function getAtkItems(Request $request)
    {
        if (!$request->ajax() && !$request->has('select') && !$request->has('q')) {
            return abort(404, 'Not Found');
        }

        $search = $request->q;

        $atkItems = Item::select('id', 'name', 'unit')
            ->orderBy('name')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->name,
                    'unit' => $item->unit,
                ];
            });

        return response()->json($atkItems);
    }

    public function index()
    {
        return view('atk.purchase-orders.index');
    }

    public function create()
    {
        return view('atk.purchase-orders.create');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        // Eager load relationships
        $purchaseOrder->load(['items', 'supplier', 'receives']);

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
            'receives'
        ));
    }

    public function store(Request $request)
    {
         $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'po_number' => 'required|string|max:50|unique:atk_purchase_orders',
            'po_date' => 'required|date',
            'schedule_date' => 'nullable|date',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.atk_item_id' => 'required|exists:atk_items,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Create Purchase Order
            $po = PurchaseOrder::create([
                'supplier_id' => $validated['supplier_id'],
                'po_number' => $validated['po_number'],
                'po_date' => $validated['po_date'],
                'schedule_date' => $validated['schedule_date'] ?? null,
                'note' => $validated['note'],
                'status' => 'open',
                'created_by' => auth()->id(),
            ]);

            // Load all units for referenced item IDs in one query
            $itemUnits = Item::whereIn('id', collect($validated['items'])->pluck('atk_item_id'))->pluck('unit', 'id');

            // Prepare items with units
            $items = collect($validated['items'])->map(function ($item) use ($po, $itemUnits) {
                return [
                    'atk_item_id' => $item['atk_item_id'],
                    'qty' => $item['qty'],
                    'unit' => $itemUnits[$item['atk_item_id']] ?? null,
                ];
            })->toArray();

            // Use Eloquent relationship to create items
            $po->items()->createMany($items);

            DB::commit();

            return redirect()->route('atk.purchase-orders.index')->with('success', 'Purchase Order created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create Purchase Order : '.$e->getMessage());
        }
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
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
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'po_number' => "required|string|max:50|unique:atk_purchase_orders,po_number,{$purchaseOrder->id}",
            'po_date' => 'required|date',
            'schedule_date' => 'nullable|date',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.atk_item_id' => 'required|exists:atk_items,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Cek status PO
            if ($purchaseOrder->status !== 'open') {
                return back()->with('error', 'Cant update Purchase Order with status ' . $purchaseOrder->status);
            }

            // Update data utama PO
            $purchaseOrder->update([
                'supplier_id' => $validated['supplier_id'],
                'po_number' => $validated['po_number'],
                'po_date' => $validated['po_date'],
                'schedule_date' => $validated['schedule_date'] ?? null,
                'note' => $validated['note'],
            ]);

            // Ambil unit items sekaligus
            $itemUnits = Item::whereIn('id', collect($validated['items'])->pluck('atk_item_id'))
                ->pluck('unit', 'id');

            // Proses sync items
            $updatedItemIds = collect($validated['items'])->pluck('atk_item_id');

            // Hapus item yang tidak ada di request
            $purchaseOrder->items()->whereNotIn('atk_item_id', $updatedItemIds)->delete();

            // Update atau buat item baru
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
            return back()
                ->withInput()
                ->with('error', 'Failed to update Purchase Order ' . $e->getMessage());
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
