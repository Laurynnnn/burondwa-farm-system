@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4" style="font-weight:600;letter-spacing:1px;">Add Unit of Measure</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('generaldata.units.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="abbreviation" class="form-label">Abbreviation</label>
                    <input type="text" name="abbreviation" id="abbreviation" class="form-control" value="{{ old('abbreviation') }}" required>
                    @error('abbreviation')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" id="description" class="form-control" value="{{ old('description') }}">
                    @error('description')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
                <a href="{{ route('generaldata.units.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection 