<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use App\Models\Atk\StockAdjustment;
use App\Models\Atk\StockAdjustmentItem;
use App\Models\Atk\Item;
use App\Models\Atk\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class AdjustmentController extends Controller
{
    public function getAdjustments(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $adjustments = StockAdjustment::with('adjustedBy', 'items.atkItem')->latest()->get();

        return response()->json($adjustments->map(function ($adj) {
            return [
                'id' => $adj->id,
                'date' => Carbon::parse($adj->date)->translatedFormat('d F Y'), // e.g. 08 June 2025
                'note' => $adj->note,
                'adjusted_by' => $adj->adjustedBy->employee->full_name ?? '-',
                'items_count' => $adj->items->count(),
            ];
        }));
    }

    public function index()
    {
        return view('atk.adjustments.index');
    }

    public function create()
    {
        $atkItems = Item::all()->keyBy('id');
        
        return view('atk.adjustments.create', [
            'atkItem' => fn($id) => $atkItems[$id] ?? null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'note' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.atk_item_id' => 'required|exists:atk_items,id',
            'items.*.adjustment_qty' => 'required|integer|not_in:0',
            'items.*.reason_type' => 'required|in:correction,loss,expired,others',
        ]);

        DB::beginTransaction();

        try {
            $adjustment = StockAdjustment::create([
                'date' => Carbon::parse($validated['date']),
                'note' => $validated['note'],
                'adjusted_by' => auth()->user()->employee_id,
            ]);

            foreach ($validated['items'] as $itemData) {
                $atkItemId = $itemData['atk_item_id'];
                $adjustQty = $itemData['adjustment_qty'];

                // Ambil ending_stock terakhir dari stok item tersebut
                $lastStock = Stock::where('atk_item_id', $atkItemId)->latest('id')->first();

                if ($lastStock) {
                    $beginingStock = $lastStock->ending_stock;
                } else {
                    $item = Item::findOrFail($atkItemId);
                    $beginingStock = $item->current_stock ?? 0;
                }
                $endingStock = $beginingStock + $adjustQty;

                if ($endingStock < 0) {
                    throw ValidationException::withMessages([
                        'items' => ["Adjustment causes negative stock for item ID: $atkItemId."]
                    ]);
                }

                // Simpan ke tabel adjustment_items
                StockAdjustmentItem::create([
                    'atk_stock_adjustment_id' => $adjustment->id,
                    'atk_item_id' => $atkItemId,
                    'adjustment_qty' => $adjustQty,
                    'reason_type' => $itemData['reason_type'],
                ]);

                // Simpan ke tabel stocks
                Stock::create([
                    'atk_item_id' => $atkItemId,
                    'type' => 'adjustment',
                    'qty' => $adjustQty,
                    'begining_stock' => $beginingStock,
                    'ending_stock' => $endingStock,
                    'note' => "Adjustment: {$itemData['reason_type']}",
                ]);

                // ✅ Update current_stock di tabel atk_items
                Item::where('id', $atkItemId)->increment('current_stock', $adjustQty);
            }

            DB::commit();
            return redirect()->route('atk.adjustments.index')->with('success', 'Stock adjustment saved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to save stock adjustment', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Adjustment failed to save.'])->withInput();
        }
    }

    public function show(StockAdjustment $adjustment)
    {
        $adjustment->load('items.atkItem:id,name,unit', 'adjustedBy.employee:id,full_name');

        return view('atk.adjustments.show', compact('adjustment'));
    }

    public function edit(StockAdjustment $adjustment)
    {
        $adjustment->load('items.atkItem', 'adjustedBy.employee');
        return view('atk.adjustments.create', compact('adjustment'));
    }

    public function update(Request $request, StockAdjustment $adjustment)
    {
        $request->validate([
            'note' => ['nullable', 'string'],
        ]);

        $adjustment->update([
            'note' => $request->note,
        ]);

        return redirect()->route('atk.adjustments.index')->with('success', 'Adjusment note updated successfully.');
    }

    // Note: No destroy() to preserve audit trail
}
