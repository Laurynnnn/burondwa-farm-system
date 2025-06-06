@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Inventory Category Details: {{ $category->name }}</h5>
                    <a href="{{ route('inventory.categories.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Categories
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 150px;">Name</th>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $category->description ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Total Items</th>
                                    <td>{{ $category->inventoryItems()->count() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h5 class="mt-4">Associated Inventory Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($category->inventoryItems as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->quantity <= $item->minimum_quantity ? 'danger' : 'success' }}">
                                                {{ $item->quantity <= $item->minimum_quantity ? 'Low Stock' : 'In Stock' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No items found in this category.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                     <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('inventory.categories.edit', $category) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit"></i> Edit Category
                        </a>
                        <form action="{{ route('inventory.categories.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                                <i class="fas fa-trash"></i> Delete Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 