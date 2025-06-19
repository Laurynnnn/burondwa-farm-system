<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Models\Sales;
use Modules\Product\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Display a listing of the sales.
     */
    public function index()
    {
        $sales = Sales::with(['product', 'creator'])
            ->latest()
            ->paginate(10);

        return view('product::sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $products = Product::where('status', 'active')->get();
        return view('product::sales.create', compact('products'));
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cash,card,mobile_money',
            'payment_status' => 'required|in:paid,pending,partial'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($validated['product_id']);
            
            // Check if there's enough stock
            if ($product->stock < $validated['quantity']) {
                return back()->with('error', 'Insufficient stock available.');
            }

            // Calculate total amount
            $validated['total_amount'] = $validated['quantity'] * $validated['unit_price'];
            $validated['created_by'] = Auth::id();

            // Create the sale
            $sale = Sales::create($validated);

            // Update product stock
            $product->decrement('stock', $validated['quantity']);

            // Log stock movement (out)
            DB::table('stock_movements')->insert([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'movement_type' => 'out',
                'user_id' => Auth::id(),
                'package_unit' => null,
                'price_per_unit' => $validated['unit_price'],
                'total' => $validated['total_amount'],
                'expiry_date' => null,
                'reference_type' => 'sale',
                'reference_id' => $sale->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('product.sales.index')
                ->with('success', 'Sale recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error recording sale: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified sale.
     */
    public function show(Sales $sale)
    {
        $sale->load(['product', 'creator']);
        return view('product::sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified sale.
     */
    public function edit(Sales $sale)
    {
        $products = Product::where('status', 'active')->get();
        return view('product::sales.edit', compact('sale', 'products'));
    }

    /**
     * Update the specified sale in storage.
     */
    public function update(Request $request, Sales $sale)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cash,card,mobile_money',
            'payment_status' => 'required|in:paid,pending,partial'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($validated['product_id']);
            
            // Calculate stock adjustment
            $stockDifference = $sale->quantity - $validated['quantity'];
            
            // Check if there's enough stock for the adjustment
            if ($stockDifference < 0 && $product->stock < abs($stockDifference)) {
                return back()->with('error', 'Insufficient stock available for adjustment.');
            }

            // Calculate total amount
            $validated['total_amount'] = $validated['quantity'] * $validated['unit_price'];

            // Update the sale
            $sale->update($validated);

            // Update product stock
            if ($stockDifference != 0) {
                $product->increment('stock', $stockDifference);
            }

            // Log stock movement (out) for updated sale
            DB::table('stock_movements')->insert([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'movement_type' => 'out',
                'user_id' => Auth::id(),
                'package_unit' => null,
                'price_per_unit' => $validated['unit_price'],
                'total' => $validated['total_amount'],
                'expiry_date' => null,
                'reference_type' => 'sale',
                'reference_id' => $sale->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('product.sales.index')
                ->with('success', 'Sale updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating sale: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(Sales $sale)
    {
        try {
            DB::beginTransaction();

            // Return the stock to the product
            $sale->product->increment('stock', $sale->quantity);

            // Log stock movement (in) for returned stock
            DB::table('stock_movements')->insert([
                'product_id' => $sale->product_id,
                'quantity' => $sale->quantity,
                'movement_type' => 'in',
                'user_id' => Auth::id(),
                'package_unit' => null,
                'price_per_unit' => $sale->unit_price,
                'total' => $sale->total_amount,
                'expiry_date' => null,
                'reference_type' => 'sale_delete',
                'reference_id' => $sale->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Delete the sale
            $sale->delete();

            DB::commit();

            return redirect()->route('product.sales.index')
                ->with('success', 'Sale deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting sale: ' . $e->getMessage());
        }
    }
}
