@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Inventory Usage History</h1>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Inventory
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Usage Records</h6>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" id="exportBtn">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usageTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Purpose</th>
                            <th>Notes</th>
                            <th>Recorded By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usageRecords as $record)
                        <tr>
                            <td>{{ $record->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $record->inventoryItem->name }}</td>
                            <td>{{ $record->inventoryItem->category->name }}</td>
                            <td>{{ $record->quantity }} {{ $record->inventoryItem->unit }}</td>
                            <td>
                                <span class="badge bg-{{ $record->purpose === 'sale' ? 'success' : 
                                    ($record->purpose === 'usage' ? 'primary' : 
                                    ($record->purpose === 'expired' ? 'danger' : 'warning')) }}">
                                    {{ ucfirst($record->purpose) }}
                                </span>
                            </td>
                            <td>{{ $record->notes }}</td>
                            <td>{{ $record->user->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Usage History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="filterForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control">
                            <span class="input-group-text">to</span>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Item</label>
                        <select name="item_id" class="form-select">
                            <option value="">All Items</option>
                            @foreach($inventoryItems as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Purpose</label>
                        <select name="purpose" class="form-select">
                            <option value="">All Purposes</option>
                            <option value="sale">Sale</option>
                            <option value="usage">Internal Usage</option>
                            <option value="expired">Expired</option>
                            <option value="damaged">Damaged</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#usageTable').DataTable({
        responsive: true,
        order: [[0, 'desc']]
    });

    // Export functionality
    $('#exportBtn').click(function() {
        window.location.href = "{{ route('inventory.usage.export') }}";
    });

    // Filter form submission
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        window.location.href = "{{ route('inventory.usage') }}?" + formData;
    });
});
</script>
@endpush
@endsection 