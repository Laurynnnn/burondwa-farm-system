@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Product Stock Sheet</h3>
        <a href="{{ route('product.stock.add') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Add Stock
        </a>
    </div>
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Current Stock</th>
                            <th>Recent Movements</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>{{ $product->unitOfMeasure->name ?? '-' }}</td>
                            <td>{{ number_format($product->stock, 2) }}</td>
                            <td>
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Qty</th>
                                            <th>User</th>
                                            <th>Expiry</th>
                                            <th>Unit</th>
                                            <th>Price/Unit</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->stockMovements()->latest()->limit(5)->get() as $move)
                                        <tr>
                                            <td>{{ $move->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $move->movement_type == 'in' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($move->movement_type) }}
                                                </span>
                                            </td>
                                            <td>{{ $move->quantity }}</td>
                                            <td>{{ $move->user->name ?? '-' }}</td>
                                            <td>{{ $move->expiry_date ?? '-' }}</td>
                                            <td>{{ $move->package_unit ?? '-' }}</td>
                                            <td>{{ $move->price_per_unit ? number_format($move->price_per_unit, 2) : '-' }}</td>
                                            <td>{{ $move->total ? number_format($move->total, 2) : '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 