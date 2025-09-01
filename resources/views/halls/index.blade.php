@extends('layouts.app')

@section('title', 'Halls List - Cinema Management')

@section('page-title', 'Halls Management')

@section('actions')
<a href="{{ route('halls.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i> Add New Hall
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Halls</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Capacity</th>
                        <th>Seats Count</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($halls as $hall)
                    <tr>
                        <td>{{ $hall->id }}</td>
                        <td>
                            <strong>{{ $hall->name }}</strong>
                        </td>
                        <td>{{ $hall->hall_type }}</td>
                        <td>{{ $hall->capacity }}</td>
                        <td>{{ $hall->seats->count() }}</td>
                        <td>
                            @if($hall->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('halls.show', $hall->id) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('halls.edit', $hall->id) }}" class="btn btn-sm btn-warning text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('halls.destroy', $hall->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this hall?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No halls found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $halls->links() }}
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Hall Distribution by Type</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($halls->groupBy('hall_type') as $type => $typeHalls)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $type }}</h5>
                                <p class="card-text">
                                    <strong>Halls:</strong> {{ $typeHalls->count() }}<br>
                                    <strong>Total Capacity:</strong> {{ $typeHalls->sum('capacity') }}<br>
                                    <strong>Average Capacity:</strong> {{ round($typeHalls->avg('capacity')) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 