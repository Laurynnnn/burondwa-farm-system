<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('items')->get();
        return view('inventory::suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('inventory::suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Supplier::create($validated);

        return redirect()->route('inventory.suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('items.category');
        return view('inventory::suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('inventory::suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $supplier->update($validated);

        return redirect()->route('inventory.suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->items()->count() > 0) {
            return redirect()->route('inventory.suppliers.index')
                ->with('error', 'Cannot delete supplier with associated items.');
        }

        $supplier->delete();

        return redirect()->route('inventory.suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
} 