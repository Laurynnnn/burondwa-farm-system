<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\InventoryItem;
use Modules\Inventory\Models\InventoryCategory;
use Modules\Inventory\Models\InventoryUsage;
use Modules\Product\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get total products
        $totalProducts = Product::count();

        // Get total inventory items
        $totalInventoryItems = InventoryItem::count();

        // Get low stock items (items with quantity <= reorder_level)
        $lowStockItems = InventoryItem::whereRaw('quantity <= reorder_level')->count();

        // Get total categories
        $totalCategories = InventoryCategory::count();

        // Get inventory distribution by category
        $categoryDistribution = InventoryCategory::withCount('inventoryItems')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->inventory_items_count
                ];
            });

        // Get low stock items list
        $lowStockItemsList = InventoryItem::with('category')
            ->whereRaw('quantity <= reorder_level')
            ->get();

        // Get monthly usage data for the last 6 months
        $monthlyUsage = InventoryUsage::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('dashboard::index', compact(
            'totalProducts',
            'totalInventoryItems',
            'lowStockItems',
            'totalCategories',
            'categoryDistribution',
            'lowStockItemsList',
            'monthlyUsage'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('dashboard::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('dashboard::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
