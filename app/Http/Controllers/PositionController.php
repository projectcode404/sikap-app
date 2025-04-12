<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    public function index()
    {
        return view('positions.index');
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
