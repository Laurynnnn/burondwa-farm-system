@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>Add Stock</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('product.stock.sheet') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Stock Sheet
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('product.stock.add') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="product_id" class="form-label">Product</label>
                        <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                            <option value="">Select a product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" step="0.01" min="0.01" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="package_unit" class="form-label">Package Unit</label>
                        <input type="text" name="package_unit" id="package_unit" class="form-control @error('package_unit') is-invalid @enderror" placeholder="e.g. tray, box">
                        @error('package_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="price_per_unit" class="form-label">Price per Unit</label>
                        <input type="number" step="0.01" min="0" name="price_per_unit" id="price_per_unit" class="form-control @error('price_per_unit') is-invalid @enderror" required>
                        @error('price_per_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="total" class="form-label">Total</label>
                        <input type="text" id="total" class="form-control" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror">
                        @error('expiry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price_per_unit');
    const totalInput = document.getElementById('total');

    function calculateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        totalInput.value = (quantity * price).toFixed(2);
    }

    quantityInput.addEventListener('input', calculateTotal);
    priceInput.addEventListener('input', calculateTotal);
});
</script>
@endpush
@endsection 