<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use App\Models\Atk\StockAtk;
use Illuminate\Http\Request;

class StockAtkController extends Controller
{
    public function getStock(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $stocks = StockAtk::select('id', 'name', 'unit', 'stock_qty', 'min_stock', 'description')
            ->orderBy('name')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'unit' => $item->unit,
                    'stock_qty' => $item->stock_qty,
                    'min_stock' => $item->min_stock,
                    'description' => $item->description ?? '-',
                ];
            });

        return response()->json($stocks);
    }
    public function index()
    {
        return view('atk.stock.index');
    }
}
