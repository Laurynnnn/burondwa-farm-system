@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Dashboard</h3>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <h6 class="card-title">Total Products</h6>
                    <h2 class="text-success">{{ $totalProducts ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <h6 class="card-title">Total Inventory Items</h6>
                    <h2 class="text-primary">{{ $totalInventoryItems ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <h6 class="card-title">Low Stock Items</h6>
                    <h2 class="text-warning">{{ $lowStockItems ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-info h-100">
                <div class="card-body text-center">
                    <h6 class="card-title">Total Categories</h6>
                    <h2 class="text-info">{{ $totalCategories ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row mb-4">
        {{-- Inventory Distribution Pie Chart --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <strong>Inventory Distribution</strong>
                </div>
                <div class="card-body">
                    <div style="position: relative; height:30vh; width:100%">
                         <canvas id="inventoryPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        {{-- Monthly Usage Bar Chart --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <strong>Monthly Usage</strong>
                </div>
                <div class="card-body">
                     <div style="position: relative; height:30vh; width:100%">
                        <canvas id="usageBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- Stock Trends Line Chart --}}
        <div class="col-md-6 mb-4">
             <div class="card h-100">
                <div class="card-header bg-white">
                    <strong>Stock Trends</strong>
                </div>
                <div class="card-body">
                    <div style="position: relative; height:30vh; width:100%">
                        <canvas id="stockLineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
         {{-- Category Breakdown Doughnut Chart --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <strong>Category Breakdown</strong>
                </div>
                <div class="card-body">
                     <div style="position: relative; height:30vh; width:100%">
                         <canvas id="categoryDoughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Low Stock Items Table --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <strong>Low Stock Items</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($lowStockItemsList) && count($lowStockItemsList))
                                    @foreach($lowStockItemsList as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->category->name ?? '-' }}</td>
                                        <td>{{ $item->stock }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">No low stock items.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dynamic Chart Data from Controller
const inventoryDistributionData = {
    labels: @json($categoryDistribution->pluck('name')),
    datasets: [{
        data: @json($categoryDistribution->pluck('count')),
        backgroundColor: [
            'rgba(76, 175, 80, 0.7)',
            'rgba(33, 150, 243, 0.7)',
            'rgba(255, 193, 7, 0.7)',
            'rgba(244, 67, 54, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 205, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)'
        ],
        borderWidth: 1
    }]
};

const monthlyUsageData = {
    labels: @json($monthlyUsage->map(fn($row) => $row->month . '-' . $row->year)),
    datasets: [{
        label: 'Usage',
        data: @json($monthlyUsage->pluck('total_quantity')),
        backgroundColor: 'rgba(33, 150, 243, 0.7)',
        borderColor: 'rgba(33, 150, 243, 1)',
        borderWidth: 1
    }]
};

const productStockData = {
    labels: @json($productStockData->pluck('name')),
    datasets: [{
        label: 'Stock Level',
        data: @json($productStockData->pluck('stock')),
        backgroundColor: 'rgba(76, 175, 80, 0.7)',
        borderColor: 'rgba(76, 175, 80, 1)',
        borderWidth: 1
    }]
};

const topSellingProductsData = {
    labels: @json($topSellingProducts->pluck('name')),
    datasets: [{
        label: 'Total Sold',
        data: @json($topSellingProducts->pluck('total_sold')),
        backgroundColor: 'rgba(255, 193, 7, 0.7)',
        borderColor: 'rgba(255, 193, 7, 1)',
        borderWidth: 1
    }]
};

const categoryBreakdownData = @json($categoryBreakdownData);
const categoryLabels = categoryBreakdownData.map(row => row.category);
const categoryStock = categoryBreakdownData.map(row => row.total_stock);
const categorySold = categoryBreakdownData.map(row => row.total_sold);

// Render Charts
new Chart(document.getElementById('inventoryPieChart').getContext('2d'), {
    type: 'pie',
    data: inventoryDistributionData,
    options: { responsive: true, maintainAspectRatio: false }
});

new Chart(document.getElementById('usageBarChart').getContext('2d'), {
    type: 'bar',
    data: monthlyUsageData,
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('stockLineChart').getContext('2d'), {
    type: 'bar',
    data: productStockData,
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('categoryDoughnutChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: categoryLabels,
        datasets: [
            {
                label: 'Total In Stock',
                data: categoryStock,
                backgroundColor: 'rgba(33, 150, 243, 0.7)',
                borderColor: 'rgba(33, 150, 243, 1)',
                borderWidth: 1
            },
            {
                label: 'Total Sold',
                data: categorySold,
                backgroundColor: 'rgba(255, 193, 7, 0.7)',
                borderColor: 'rgba(255, 193, 7, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush
@endsection
