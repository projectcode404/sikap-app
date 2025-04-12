<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;

class DivisionController extends Controller
{
    public function index()
    {
        return view('divisions.index');
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
