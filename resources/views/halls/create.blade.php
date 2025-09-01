@extends('layouts.app')

@section('title', 'Add New Hall - Cinema Management')

@section('page-title', 'Add New Hall')

@section('actions')
<a href="{{ route('dashboard.halls') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back to Halls
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Hall Information</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('halls.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Hall Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
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
                            <option value="Regular" {{ old('hall_type') == 'Regular' ? 'selected' : '' }}>Regular</option>
                            <option value="IMAX" {{ old('hall_type') == 'IMAX' ? 'selected' : '' }}>IMAX</option>
                            <option value="VIP" {{ old('hall_type') == 'VIP' ? 'selected' : '' }}>VIP</option>
                            <option value="3D" {{ old('hall_type') == '3D' ? 'selected' : '' }}>3D</option>
                            <option value="4DX" {{ old('hall_type') == '4DX' ? 'selected' : '' }}>4DX</option>
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
                        <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity') }}" min="1" required>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="is_active" class="form-label d-block">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
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
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Hall
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Hall Layout Tips</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6><i class="fas fa-info-circle text-info me-2"></i> Hall Configuration</h6>
                <ul>
                    <li>Regular halls typically have 100-150 seats</li>
                    <li>IMAX halls typically have 200-300 seats</li>
                    <li>VIP halls typically have 50-80 premium seats</li>
                    <li>3D and 4DX halls require special equipment</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6><i class="fas fa-lightbulb text-warning me-2"></i> Next Steps</h6>
                <p>After creating a hall, you can:</p>
                <ul>
                    <li>Add seats to the hall</li>
                    <li>Configure seat types and pricing</li>
                    <li>Assign movies to the hall</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 