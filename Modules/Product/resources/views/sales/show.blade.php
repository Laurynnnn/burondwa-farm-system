@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>Sale Details</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('product.sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Sales
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Sale Information</h4>
                    <table class="table">
                        <tr>
                            <th>Date</th>
                            <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Product</th>
                            <td>{{ $sale->product->name }}</td>
                        </tr>
                        <tr>
                            <th>Quantity</th>
                            <td>{{ $sale->quantity }}</td>
                        </tr>
                        <tr>
                            <th>Unit Price</th>
                            <td>{{ number_format($sale->unit_price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td>{{ number_format($sale->total_amount, 2) }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h4>Customer Information</h4>
                    <table class="table">
                        <tr>
                            <th>Customer Name</th>
                            <td>{{ $sale->customer_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Customer Phone</th>
                            <td>{{ $sale->customer_phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td>{{ ucfirst($sale->payment_method) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Status</th>
                            <td>
                                <span class="badge bg-{{ $sale->payment_status === 'paid' ? 'success' : ($sale->payment_status === 'partial' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($sale->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td>{{ $sale->creator->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($sale->notes)
                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Notes</h4>
                        <div class="card">
                            <div class="card-body">
                                {{ $sale->notes }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row mt-4">
                <div class="col-12 text-end">
                    <a href="{{ route('product.sales.edit', $sale) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Sale
                    </a>
                    <form action="{{ route('product.sales.destroy', $sale) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this sale?')">
                            <i class="fas fa-trash"></i> Delete Sale
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 