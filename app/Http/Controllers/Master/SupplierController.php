<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function getSuppliers(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $suppliers = Supplier::all();
        return response()->json($suppliers);
    }
    
    public function index()
    {
        return view('master.suppliers.index');
    }

    public function create()
    {
        return view('master.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pic' => 'nullable|string|max:50',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'bank_name' => 'nullable|string|max:50',
            'bank_account' => 'nullable|string|max:50',
            'address' => 'required|string|max:512',
        ]);

        Supplier::create($request->all());

        return redirect()->route('master.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        return view('master.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('master.suppliers.create', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pic' => 'nullable|string|max:50',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'bank_name' => 'nullable|string|max:50',
            'bank_account' => 'nullable|string|max:50',
            'address' => 'required|string|max:512',
        ]);

        $supplier->update($request->all());

        return redirect()->route('master.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        if (request()->ajax() || request()->expectsJson()) {
            return response()->json(['message' => 'Supplier deleted successfully.'], 200);
        }

        return redirect()->route('master.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
