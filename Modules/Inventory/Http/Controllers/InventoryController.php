<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\InventoryItem;
use Modules\Inventory\Models\InventoryCategory;
use Modules\Inventory\Models\InventoryUsage;
use Modules\Inventory\Models\Supplier;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventoryItems = InventoryItem::with(['category', 'supplier'])->get();
        return view('inventory::index', compact('inventoryItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = InventoryCategory::all();
        $suppliers = Supplier::where('is_active', true)->get();
        return view('inventory::create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:inventory_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'minimum_quantity' => 'required|numeric|min:0'
        ]);

        InventoryItem::create($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show(InventoryItem $inventoryItem)
    {
        $inventoryItem->load(['category', 'supplier', 'usageHistory']);
        return view('inventory::show', compact('inventoryItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryItem $inventoryItem)
    {
        $categories = InventoryCategory::all();
        $suppliers = Supplier::where('is_active', true)->get();
        return view('inventory::edit', compact('inventoryItem', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryItem $inventoryItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:inventory_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'minimum_quantity' => 'required|numeric|min:0'
        ]);

        $inventoryItem->update($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryItem $inventoryItem)
    {
        $inventoryItem->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    public function usage(Request $request)
    {
        $query = InventoryUsage::with('inventoryItem.category');

        if ($request->filled('item')) {
            $query->where('inventory_item_id', $request->item);
        }

        if ($request->filled('purpose')) {
            $query->where('purpose', $request->purpose);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $usageHistory = $query->latest()->paginate(25);
        $inventoryItems = InventoryItem::all();

        return view('inventory::usage', compact('usageHistory', 'inventoryItems'));
    }

    public function exportUsage(Request $request)
    {
        $query = InventoryUsage::with('inventoryItem.category');

        if ($request->filled('item')) {
            $query->where('inventory_item_id', $request->item);
        }

        if ($request->filled('purpose')) {
            $query->where('purpose', $request->purpose);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $usageHistory = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inventory_usage.csv"',
        ];

        $callback = function() use ($usageHistory) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Item', 'Category', 'Quantity', 'Purpose', 'Notes']);

            foreach ($usageHistory as $usage) {
                fputcsv($file, [
                    $usage->created_at->format('Y-m-d H:i:s'),
                    $usage->inventoryItem->name,
                    $usage->inventoryItem->category->name,
                    $usage->quantity . ' ' . $usage->inventoryItem->unit,
                    $usage->purpose,
                    $usage->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function reports()
    {
        $totalItems = InventoryItem::count();
        $totalValue = InventoryItem::sum(DB::raw('quantity * unit_price'));
        $lowStockCount = InventoryItem::whereRaw('quantity <= minimum_quantity')->count();
        $outOfStockCount = InventoryItem::where('quantity', '<=', 0)->count();

        $monthlyUsage = InventoryUsage::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(quantity) as total')
        )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $categoryDistribution = InventoryCategory::withCount('items')
            ->get();

        $lowStockItems = InventoryItem::with('category')
            ->whereRaw('quantity <= minimum_quantity')
            ->get();

        $topUsedItems = InventoryItem::with('category')
            ->withCount(['usageHistory as total_usage' => function($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->withMax('usageHistory', 'created_at')
            ->orderByDesc('total_usage')
            ->limit(10)
            ->get();

        return view('inventory::reports', compact(
            'totalItems',
            'totalValue',
            'lowStockCount',
            'outOfStockCount',
            'monthlyUsage',
            'categoryDistribution',
            'lowStockItems',
            'topUsedItems'
        ));
    }
}
