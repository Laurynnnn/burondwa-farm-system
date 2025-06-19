<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductCategory;
use Modules\GeneralData\Models\UnitOfMeasure;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();
        return view('product::index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProductCategory::all();
        $units = UnitOfMeasure::orderBy('name')->get();
        return view('product::create', compact('categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:product_categories,id',
            'status' => 'required|in:active,inactive',
            'unit_of_measure_id' => 'nullable|exists:units_of_measure,id',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show(Product $product)
    {
        return view('product::show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        $units = UnitOfMeasure::orderBy('name')->get();
        return view('product::edit', compact('product', 'categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:product_categories,id',
            'status' => 'required|in:active,inactive',
            'unit_of_measure_id' => 'nullable|exists:units_of_measure,id',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Display a stock sheet for all products.
     */
    public function stockSheet()
    {
        $products = Product::with('category', 'unitOfMeasure')->get();
        return view('product::stock.sheet', compact('products'));
    }

    /**
     * Show the form for adding stock to a product.
     */
    public function stockEntryForm()
    {
        $products = Product::where('status', 'active')->get();
        return view('product::stock.add', compact('products'));
    }

    /**
     * Store a new stock entry and log the movement.
     */
    public function storeStockEntry(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'package_unit' => 'nullable|string|max:255',
            'price_per_unit' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $total = $validated['quantity'] * $validated['price_per_unit'];

        // Update product stock
        $product->increment('stock', $validated['quantity']);

        // Log stock movement
        DB::table('stock_movements')->insert([
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'movement_type' => 'in',
            'user_id' => $request->user()->id,
            'package_unit' => $validated['package_unit'] ?? null,
            'price_per_unit' => $validated['price_per_unit'],
            'total' => $total,
            'expiry_date' => $validated['expiry_date'] ?? null,
            'reference_type' => 'manual',
            'reference_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('product.stock.sheet')->with('success', 'Stock added successfully.');
    }
}
