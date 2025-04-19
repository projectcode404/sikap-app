<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        return view('employee.positions.index');
    }

    public function getPositions(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $positions = Position::all();
        return response()->json($positions);
    }
}
