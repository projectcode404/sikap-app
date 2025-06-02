<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\WorkUnit;
use Illuminate\Http\Request;

class WorkUnitController extends Controller
{
    public function getWorkUnitSelect(Request $request)
    {
        if (!$request->ajax() && !$request->has('select') && !$request->has('q')) {
            return abort(404, 'Not Found');
        }

        $search = $request->q;

        $workUnits = WorkUnit::select('id', 'name')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->orderBy('name')
            ->get()
            ->map(function ($unit) {
                return [
                    'id' => $unit->id,
                    'text' => $unit->name,
                ];
            });

        return response()->json($workUnits);
    }

    public function index()
    {
        return view('master.work-units.index');
    }

    public function getWorkUnits(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }
        
        $workUnits = WorkUnit::all();
        return response()->json($workUnits);
    }
}
