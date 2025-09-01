@extends('layouts.app')

@section('title', 'Edit Hall - Cinema Management')

@section('page-title', 'Edit Hall')

@section('actions')
<a href="{{ route('dashboard.halls') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back to Halls
</a>
<a href="{{ route('halls.show', $hall) }}" class="btn btn-info ms-2">
    <i class="fas fa-eye me-1"></i> View Hall
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Hall: {{ $hall->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('halls.update', $hall->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Hall Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $hall->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="hall_type" class="form-label">Hall Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('hall_type') is-invalid @enderror" id="hall_type" name="hall_type" required>
                            <option value="">Select Hall Type</option>
                            <option value="Regular" {{ old('hall_type', $hall->hall_type) == 'Regular' ? 'selected' : '' }}>Regular</option>
                            <option value="IMAX" {{ old('hall_type', $hall->hall_type) == 'IMAX' ? 'selected' : '' }}>IMAX</option>
                            <option value="VIP" {{ old('hall_type', $hall->hall_type) == 'VIP' ? 'selected' : '' }}>VIP</option>
                            <option value="3D" {{ old('hall_type', $hall->hall_type) == '3D' ? 'selected' : '' }}>3D</option>
                            <option value="4DX" {{ old('hall_type', $hall->hall_type) == '4DX' ? 'selected' : '' }}>4DX</option>
                        </select>
                        @error('hall_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $hall->capacity) }}" min="1" required>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="is_active" class="form-label d-block">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $hall->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $hall->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('halls.show', $hall->id) }}" class="btn btn-info text-white">
                    <i class="fas fa-eye me-1"></i> View Details
                </a>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Hall
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Seats in this Hall</h5>
        <a href="{{ route('seats.create') }}?hall_id={{ $hall->id }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> Add Seats
        </a>
    </div>
    <div class="card-body">
        @if($hall->seats->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Row</th>
                            <th>Number</th>
                            <th>Type</th>
                            <th>Additional Charge</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hall->seats as $seat)
                        <tr>
                            <td>{{ $seat->row }}</td>
                            <td>{{ $seat->number }}</td>
                            <td>{{ ucfirst($seat->type) }}</td>
                            <td>${{ number_format($seat->additional_charge, 2) }}</td>
                            <td>
                                @if($seat->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Unavailable</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('seats.edit', $seat->id) }}" class="btn btn-sm btn-warning text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No seats have been added to this hall yet.
                <a href="{{ route('seats.create') }}?hall_id={{ $hall->id }}" class="alert-link">Add seats now</a>.
            </div>
        @endif
    </div>
</div>
@endsection 