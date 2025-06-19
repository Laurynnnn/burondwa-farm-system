@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4" style="font-weight:600;letter-spacing:1px;">Units of Measure</h2>
    <a href="{{ route('generaldata.units.create') }}" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Add Unit</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Abbreviation</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($units as $unit)
                        <tr>
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->abbreviation }}</td>
                            <td>{{ $unit->description }}</td>
                            <td>
                                <a href="{{ route('generaldata.units.edit', $unit) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                <form action="{{ route('generaldata.units.destroy', $unit) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this unit?')"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $units->links() }}
    </div>
</div>
@endsection 