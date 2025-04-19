<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function createForm()
    {
        // Tampilkan form permintaan ATK manual
    }

    public function storeFromForm(Request $request)
    {
        // Proses simpan dari form web
    }

    public function showUploadForm()
    {
        // Tampilkan form upload Excel
    }

    public function preview(Request $request)
    {
        // Pratinjau data dari Excel
    }

    public function store(Request $request)
    {
        // Simpan data hasil upload Excel
    }
}
