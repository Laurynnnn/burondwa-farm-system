@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Inventory Item</h3>
                    <div class="card-tools">
                        <a href="{{ route('inventory.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to Inventory
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventory.update', $inventoryItem) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Item Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $inventoryItem->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $inventoryItem->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" step="0.01" class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" value="{{ old('quantity', $inventoryItem->quantity) }}" required>
                                    @error('quantity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit">Unit</label>
                                    <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                           id="unit" name="unit" value="{{ old('unit', $inventoryItem->unit) }}" required>
                                    @error('unit')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="minimum_quantity">Minimum Quantity</label>
                                    <input type="number" step="0.01" class="form-control @error('minimum_quantity') is-invalid @enderror" 
                                           id="minimum_quantity" name="minimum_quantity" value="{{ old('minimum_quantity', $inventoryItem->minimum_quantity) }}" required>
                                    @error('minimum_quantity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier_id">Supplier</label>
                                    <select class="form-control @error('supplier_id') is-invalid @enderror" 
                                            id="supplier_id" name="supplier_id">
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" 
                                                {{ old('supplier_id', $inventoryItem->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4">{{ old('description', $inventoryItem->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Item
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 