@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Inventory Item Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('inventory.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to Inventory
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">Name</th>
                                    <td>{{ $inventoryItem->name }}</td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>{{ $inventoryItem->category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Quantity</th>
                                    <td>{{ $inventoryItem->quantity }} {{ $inventoryItem->unit }}</td>
                                </tr>
                                <tr>
                                    <th>Minimum Quantity</th>
                                    <td>{{ $inventoryItem->minimum_quantity }} {{ $inventoryItem->unit }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $inventoryItem->quantity <= $inventoryItem->minimum_quantity ? 'danger' : 'success' }}">
                                            {{ $inventoryItem->quantity <= $inventoryItem->minimum_quantity ? 'Low Stock' : 'In Stock' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Supplier</th>
                                    <td>{{ $inventoryItem->supplier ? $inventoryItem->supplier->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $inventoryItem->created_at->format('M d, Y H:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td>{{ $inventoryItem->updated_at->format('M d, Y H:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Description</h4>
                                </div>
                                <div class="card-body">
                                    {{ $inventoryItem->description ?: 'No description available.' }}
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4 class="card-title">Recent Usage History</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Quantity</th>
                                                    <th>Purpose</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($inventoryItem->usageHistory as $usage)
                                                <tr>
                                                    <td>{{ $usage->created_at->format('M d, Y') }}</td>
                                                    <td>{{ $usage->quantity }} {{ $inventoryItem->unit }}</td>
                                                    <td>{{ ucfirst($usage->purpose) }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">No usage history available.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                <a href="{{ route('inventory.edit', $inventoryItem) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit Item
                                </a>
                                <form action="{{ route('inventory.destroy', $inventoryItem) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                        <i class="fas fa-trash"></i> Delete Item
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 