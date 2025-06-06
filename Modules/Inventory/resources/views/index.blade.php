@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Inventory Items</h3>
                    <div class="card-tools">
                        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Item
                    </a>
                </div>
            </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
    </div>
    @endif

            <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventoryItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->unit }}</td>
                                    <td>
                                        <span class="badge badge-{{ $item->quantity <= $item->minimum_quantity ? 'danger' : 'success' }}">
                                            {{ $item->quantity <= $item->minimum_quantity ? 'Low Stock' : 'In Stock' }}
                                        </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                            <a href="{{ route('inventory.show', $item) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                    <a href="{{ route('inventory.edit', $item) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#inventoryTable').DataTable({
        responsive: true,
        order: [[0, 'asc']]
    });
});
</script>
@endpush
@endsection
