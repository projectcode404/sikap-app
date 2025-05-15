<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReceiveAtkController extends Controller
{
    public function index()
    {
        // Tampilkan daftar penerimaan ATK
    }

    public function create($po_id)
    {
        // Tampilkan form penerimaan berdasarkan PO
    }

    public function store(Request $request)
    {
        // Simpan data penerimaan
    }
}
