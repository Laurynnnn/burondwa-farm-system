<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\InventoryCategory;

class InventoryCategoryController extends Controller
{
    public function index()
    {
        $categories = InventoryCategory::withCount('inventoryItems')->get();
        return view('inventory::categories.index', compact('categories'));
    }

    public function create()
    {
        return view('inventory::categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories',
            'description' => 'nullable|string'
        ]);

        InventoryCategory::create($validated);

        return redirect()->route('inventory.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryCategory $category)
    {
        $category->loadCount('inventoryItems');
        return view('inventory::categories.show', compact('category'));
    }

    public function edit(InventoryCategory $category)
    {
        return view('inventory::categories.edit', compact('category'));
    }

    public function update(Request $request, InventoryCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        $category->update($validated);

        return redirect()->route('inventory.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(InventoryCategory $category)
    {
        if ($category->inventoryItems()->count() > 0) {
            return redirect()->route('inventory.categories.index')
                ->with('error', 'Cannot delete category with associated items.');
        }

        $category->delete();

        return redirect()->route('inventory.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
} 