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
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($lowStockItemsList) && count($lowStockItemsList))
                                    @foreach($lowStockItemsList as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->category->name ?? '-' }}</td>
                                        <td>{{ $item->quantity }}</td>
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
// Chart Data (Static for now)
const inventoryDistributionData = {
    labels: ['Seeds', 'Fertilizer', 'Pesticides', 'Tools'],
    datasets: [{
        data: [12, 19, 7, 5],
        backgroundColor: [
            'rgba(76, 175, 80, 0.7)', // Green
            'rgba(33, 150, 243, 0.7)', // Blue
            'rgba(255, 193, 7, 0.7)', // Yellow
            'rgba(244, 67, 54, 0.7)'  // Red
        ],
        borderColor: [
            'rgba(76, 175, 80, 1)',
            'rgba(33, 150, 243, 1)',
            'rgba(255, 193, 7, 1)',
            'rgba(244, 67, 54, 1)'
        ],
        borderWidth: 1
    }]
};

const monthlyUsageData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    datasets: [{
        label: 'Usage',
        data: [30, 45, 28, 60, 40, 55],
        backgroundColor: 'rgba(33, 150, 243, 0.7)',
        borderColor: 'rgba(33, 150, 243, 1)',
        borderWidth: 1
    }]
};

const stockTrendsData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    datasets: [{
        label: 'Stock Level',
        data: [150, 140, 160, 155, 170, 165],
        borderColor: 'rgba(76, 175, 80, 1)',
        backgroundColor: 'rgba(76, 175, 80, 0.2)',
        fill: true,
        tension: 0.4
    }]
};

const categoryBreakdownData = {
    labels: ['Produce', 'Livestock', 'Supplies', 'Equipment'],
    datasets: [{
        data: [40, 25, 20, 15],
         backgroundColor: [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 205, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)'
        ],
         borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 205, 86, 1)',
            'rgba(75, 192, 192, 1)'
        ],
        borderWidth: 1
    }]
};

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
    type: 'line',
    data: stockTrendsData,
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('categoryDoughnutChart').getContext('2d'), {
    type: 'doughnut',
    data: categoryBreakdownData,
    options: { responsive: true, maintainAspectRatio: false }
});

</script>
@endpush
@endsection
