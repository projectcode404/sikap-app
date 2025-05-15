<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Atk\PoAtk;

class PoAtkController extends Controller
{
    public function getPoAtk(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $poAtk = PoAtk::all();
        return response()->json($poAtk);
    }

    public function index()
    {
        // Tampilkan daftar PO
        return view('atk.po-atk.index');
    }

    public function create()
    {
        // Form input PO baru
    }

    public function store(Request $request)
    {
        // Simpan data PO
    }
}
