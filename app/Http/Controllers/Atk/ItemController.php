<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use App\Models\Atk\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function getItems(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $items = Item::select('id', 'name', 'unit', 'current_stock', 'min_stock', 'description')
            ->orderBy('name')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'unit' => $item->unit,
                    'current_stock' => $item->current_stock,
                    'min_stock' => $item->min_stock,
                    'description' => $item->description ?? '-',
                ];
            });

        return response()->json($items);
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
        return view('atk.items.index');
    }
    
    public function create()
    {
        return view('atk.items.create');
    }
}
