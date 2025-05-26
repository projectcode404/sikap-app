<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use App\Models\Atk\Adjustment;
use App\Models\Atk\Item;
use App\Models\Atk\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AdjustmentController extends Controller
{
    public function getAdjustments(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $adjustments = Adjustment::with([
            'item:id,name,unit',
            'adjustor.employee:id,full_name'
        ])->latest()->get();

        $data = $adjustments->map(function ($adj) {
            return [
                'id' => $adj->id,
                'item_name' => $adj->item->name ?? '-',
                'unit' => $adj->item->unit ?? '-',
                'adjustment_qty' => $adj->adjustment_qty,
                'reason_type' => $adj->reason_type,
                'note' => $adj->note ?? '-',
                'date' => $adj->date?->format('Y-m-d'),
                'adjusted_by' => optional($adj->adjustor?->employee)->full_name ?? '-',
            ];
        });

        return response()->json($data);
    }

    public function index()
    {
        return view('atk.adjustments.index');
    }

    public function create()
    {
        $atkItems = Item::all();
        return view('atk.adjustments.create', compact('atkItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'atk_item_id' => 'required|exists:atk_items,id',
            'adjustment_qty' => 'required|integer|not_in:0',
            'reason_type' => 'required|in:correction,loss,expired,others',
            'note' => 'nullable|string',
            'date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $adjustment = Adjustment::create([
                'atk_item_id' => $validated['atk_item_id'],
                'adjustment_qty' => $validated['adjustment_qty'],
                'reason_type' => $validated['reason_type'],
                'note' => $validated['note'] ?? null,
                'date' => Carbon::parse($validated['date']),
                'adjusted_by' => Auth::id(),
            ]);

            Stock::create([
                'atk_item_id' => $adjustment->atk_item_id,
                'type' => 'adjustment',
                'qty' => $adjustment->adjustment_qty,
                'note' => "Adjustment: {$adjustment->reason_type}",
            ]);

            $adjustment->item->increment('current_stock', $adjustment->adjustment_qty);

            DB::commit();
            return redirect()->route('atk.adjustments.index')->with('success', 'Adjustment saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Adjustment error', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to save adjustment.'])->withInput();
        }
    }

    public function show(Adjustment $adjustment)
    {
        $adjustment->load('item');
        return view('atk.adjustments.show', compact('adjustment'));
    }

    public function edit(Adjustment $adjustment)
    {
        $adjustment->load('item');
        return view('atk.adjustments.edit', compact('adjustment'));
    }

    public function update(Request $request, Adjustment $adjustment)
    {
        $validated = $request->validate([
            'note' => 'nullable|string',
        ]);

        $adjustment->update([
            'note' => $validated['note'],
        ]);

        return redirect()->route('atk.adjustments.index')->with('success', 'Note updated successfully.');
    }

    // Note: No destroy() to preserve audit trail
}