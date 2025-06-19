@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>Edit Sale</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('product.sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Sales
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('product.sales.update', $sale) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                <option value="">Select a product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                        data-price="{{ $product->price }}" 
                                        data-stock="{{ $product->stock }}"
                                        {{ $sale->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (Stock: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" step="0.01" min="0.01" name="quantity" id="quantity" 
                                class="form-control @error('quantity') is-invalid @enderror" 
                                value="{{ old('quantity', $sale->quantity) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="unit_price" class="form-label">Unit Price</label>
                            <input type="number" step="0.01" min="0" name="unit_price" id="unit_price" class="form-control" readonly value="{{ old('unit_price', $sale->unit_price) }}">
                        </div>

                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input type="text" id="total_amount" class="form-control" 
                                value="{{ number_format($sale->total_amount, 2) }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" 
                                class="form-control @error('customer_name') is-invalid @enderror" 
                                value="{{ old('customer_name', $sale->customer_name) }}">
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Customer Phone</label>
                            <input type="text" name="customer_phone" id="customer_phone" 
                                class="form-control @error('customer_phone') is-invalid @enderror" 
                                value="{{ old('customer_phone', $sale->customer_phone) }}">
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                <option value="cash" {{ $sale->payment_method === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ $sale->payment_method === 'card' ? 'selected' : '' }}>Card</option>
                                <option value="mobile_money" {{ $sale->payment_method === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
                                <option value="paid" {{ $sale->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ $sale->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="partial" {{ $sale->payment_status === 'partial' ? 'selected' : '' }}>Partial</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $sale->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Sale
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const unitPriceInput = document.getElementById('unit_price');
    const totalAmountInput = document.getElementById('total_amount');

    function calculateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        const total = quantity * unitPrice;
        totalAmountInput.value = total.toFixed(2);
    }

    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            unitPriceInput.value = selectedOption.dataset.price;
            calculateTotal();
        } else {
            unitPriceInput.value = '';
            totalAmountInput.value = '';
        }
    });

    quantityInput.addEventListener('input', calculateTotal);
});
</script>
@endpush
@endsection 