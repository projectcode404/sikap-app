<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkUnit;

class WorkUnitController extends Controller
{
    public function index()
    {
        return view('work-units.index');
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
