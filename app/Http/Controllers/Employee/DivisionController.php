<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee\Division;

class DivisionController extends Controller
{
    public function index()
    {
        return view('employee.divisions.index');
    }

    public function getDivisions(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $divisions = Division::all();
        return response()->json($divisions);
    }
}
