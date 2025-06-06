@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Inventory Reports</h3>
                    <div class="card-tools">
                        <a href="{{ route('inventory.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to Inventory
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalItems }}</h3>
                                    <p>Total Items</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-boxes"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $totalValue }}</h3>
                                    <p>Total Value</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $lowStockCount }}</h3>
                                    <p>Low Stock Items</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $outOfStockCount }}</h3>
                                    <p>Out of Stock Items</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Monthly Usage</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="monthlyUsageChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Category Distribution</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="categoryDistributionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Items -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Low Stock Items</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Category</th>
                                                    <th>Current Stock</th>
                                                    <th>Minimum Stock</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($lowStockItems as $item)
                                                <tr>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->category->name }}</td>
                                                    <td>{{ $item->quantity }} {{ $item->unit }}</td>
                                                    <td>{{ $item->minimum_quantity }} {{ $item->unit }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $item->quantity <= 0 ? 'danger' : 'warning' }}">
                                                            {{ $item->quantity <= 0 ? 'Out of Stock' : 'Low Stock' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No low stock items found.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Used Items -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Top Used Items</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Category</th>
                                                    <th>Total Usage</th>
                                                    <th>Last Used</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($topUsedItems as $item)
                                                <tr>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->category->name }}</td>
                                                    <td>{{ $item->total_usage }} {{ $item->unit }}</td>
                                                    <td>{{ $item->last_used ? $item->last_used->format('M d, Y') : 'Never' }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No usage data available.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Monthly Usage Chart
    var monthlyUsageCtx = document.getElementById('monthlyUsageChart').getContext('2d');
    new Chart(monthlyUsageCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyUsage->pluck('month')) !!},
            datasets: [{
                label: 'Usage',
                data: {!! json_encode($monthlyUsage->pluck('total')) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Category Distribution Chart
    var categoryDistributionCtx = document.getElementById('categoryDistributionChart').getContext('2d');
    new Chart(categoryDistributionCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($categoryDistribution->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($categoryDistribution->pluck('count')) !!},
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ]
            }]
        },
        options: {
            responsive: true
        }
    });
});
</script>
@endpush
@endsection 