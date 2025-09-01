@extends('layouts.app')

@section('title', 'Edit Seat - Cinema Management')

@section('page-title', 'Edit Seat')

@section('actions')
<a href="{{ route('dashboard.seats') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back to Seats
</a>
<a href="{{ route('seats.show', $seat) }}" class="btn btn-info ms-2">
    <i class="fas fa-eye me-1"></i> View Seat
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Seat Information</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
                @endif
                
                <form action="{{ route('seats.update', $seat) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hall_id" class="form-label">Hall <span class="text-danger">*</span></label>
                                <select class="form-select @error('hall_id') is-invalid @enderror" id="hall_id" name="hall_id" required>
                                    <option value="">Select Hall</option>
                                    @foreach($halls as $hall)
                                        <option value="{{ $hall->id }}" {{ old('hall_id', $seat->hall_id) == $hall->id ? 'selected' : '' }}>
                                            {{ $hall->name }} ({{ $hall->hall_type }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('hall_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Seat Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="regular" {{ old('type', $seat->type) == 'regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="premium" {{ old('type', $seat->type) == 'premium' ? 'selected' : '' }}>Premium</option>
                                    <option value="vip" {{ old('type', $seat->type) == 'vip' ? 'selected' : '' }}>VIP</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="row" class="form-label">Row <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('row') is-invalid @enderror" id="row" name="row" value="{{ old('row', $seat->row) }}" required maxlength="10">
                                <div class="form-text">Row identifier (e.g. A, B, C or 1, 2, 3)</div>
                                @error('row')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="number" class="form-label">Seat Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number', $seat->number) }}" required min="1">
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="additional_charge" class="form-label">Additional Charge <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('additional_charge') is-invalid @enderror" id="additional_charge" name="additional_charge" value="{{ old('additional_charge', $seat->additional_charge) }}" step="0.01" min="0" required>
                                    @error('additional_charge')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Extra charge for this seat type added to base ticket price</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="is_available" class="form-label d-block">Availability</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('is_available') is-invalid @enderror" type="checkbox" role="switch" id="is_available" name="is_available" value="1" {{ old('is_available', $seat->is_available) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_available">Available</label>
                                    @error('is_available')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('seats.show', $seat) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Seat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Current Seat</h5>
            </div>
            <div class="card-body text-center py-4">
                <div class="d-flex justify-content-center mb-4">
                    <div class="screen-indicator bg-secondary text-white py-2 px-5 rounded">
                        SCREEN
                    </div>
                </div>
                
                <div class="seat-visual mb-4">
                    @php
                        $seatClass = '';
                        switch($seat->type) {
                            case 'regular': $seatClass = 'bg-info'; break;
                            case 'premium': $seatClass = 'bg-warning'; break;
                            case 'vip': $seatClass = 'bg-danger text-white'; break;
                        }
                        
                        $statusClass = $seat->is_available ? '' : 'opacity-50';
                    @endphp
                    
                    <div class="position-relative d-inline-block">
                        <div class="seat-icon {{ $seatClass }} {{ $statusClass }}" style="width: 60px; height: 60px; border-radius: 10px 10px 20px 20px; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: bold;">
                            {{ $seat->row }}{{ $seat->number }}
                        </div>
                        
                        @if(!$seat->is_available)
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-ban text-danger fa-2x"></i>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="seat-info">
                    <p class="mb-1">
                        <strong>Hall:</strong> {{ $seat->hall->name ?? 'N/A' }}
                    </p>
                    <p class="mb-1">
                        <strong>Current Type:</strong> {{ ucfirst($seat->type) }}
                    </p>
                    <p class="mb-1">
                        <strong>Current Charge:</strong> ${{ number_format($seat->additional_charge, 2) }}
                    </p>
                    <p class="mb-0">
                        <strong>Current Status:</strong> 
                        @if($seat->is_available)
                            <span class="text-success">Available</span>
                        @else
                            <span class="text-secondary">Unavailable</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Helpful Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i> 
                        <strong>Regular seats</strong> typically have no additional charge.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i> 
                        <strong>Premium seats</strong> are better positioned and have a moderate additional charge.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i> 
                        <strong>VIP seats</strong> offer the best experience and have a premium additional charge.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i> 
                        Changing the hall, row, or seat number might create conflicts if another seat with those details already exists.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 