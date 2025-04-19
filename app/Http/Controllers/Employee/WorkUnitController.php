<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee\WorkUnit;
use Illuminate\Http\Request;

class WorkUnitController extends Controller
{
    public function index()
    {
        return view('employee.work-units.index');
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
