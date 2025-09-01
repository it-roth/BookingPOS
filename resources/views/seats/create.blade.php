@extends('layouts.app')

@section('title', 'Add New Seat - Cinema Management')

@section('page-title', 'Add New Seat')

@section('actions')
<a href="{{ route('dashboard.seats') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back to Seats
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Seat Information</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
                @endif
                
                <form action="{{ route('seats.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hall_id" class="form-label">Hall <span class="text-danger">*</span></label>
                                <select class="form-select @error('hall_id') is-invalid @enderror" id="hall_id" name="hall_id" required>
                                    <option value="">Select Hall</option>
                                    @foreach($halls as $hall)
                                        <option value="{{ $hall->id }}" {{ old('hall_id', request('hall_id')) == $hall->id ? 'selected' : '' }}>
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
                                    <option value="regular" {{ old('type') == 'regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="premium" {{ old('type') == 'premium' ? 'selected' : '' }}>Premium</option>
                                    <option value="vip" {{ old('type') == 'vip' ? 'selected' : '' }}>VIP</option>
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
                                <input type="text" class="form-control @error('row') is-invalid @enderror" id="row" name="row" value="{{ old('row') }}" required maxlength="10">
                                <div class="form-text">Row identifier (e.g. A, B, C or 1, 2, 3)</div>
                                @error('row')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="number" class="form-label">Seat Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number') }}" required min="1">
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
                                    <input type="number" class="form-control @error('additional_charge') is-invalid @enderror" id="additional_charge" name="additional_charge" value="{{ old('additional_charge', '0.00') }}" step="0.01" min="0" required>
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
                                    <input class="form-check-input @error('is_available') is-invalid @enderror" type="checkbox" role="switch" id="is_available" name="is_available" value="1" {{ old('is_available', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_available">Available</label>
                                    @error('is_available')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Seat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-primary-subtle shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-th-large me-2"></i> Bulk Seat Creation</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-magic fa-2x me-3"></i>
                    <div>
                        <strong>Create multiple seats at once!</strong>
                        <p class="mb-0 small">Save time by adding entire rows or sections with a few clicks.</p>
                    </div>
                </div>
                
                <form action="{{ route('seats.bulkStore') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="hall_id" value="{{ request('hall_id') }}">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="fas fa-arrows-alt-h me-2 text-primary"></i>Row Range</label>
                        <div class="d-flex gap-2">
                            <div class="input-group">
                                <span class="input-group-text bg-light">From</span>
                                <input type="text" class="form-control" name="row_start" placeholder="e.g. A" maxlength="2" required>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light">To</span>
                                <input type="text" class="form-control" name="row_end" placeholder="e.g. E" maxlength="2" required>
                            </div>
                        </div>
                        <small class="text-muted">Use letters like A, B, C for rows</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="fas fa-hashtag me-2 text-primary"></i>Seat Number Range</label>
                        <div class="d-flex gap-2">
                            <div class="input-group">
                                <span class="input-group-text bg-light">From</span>
                                <input type="number" class="form-control" name="number_start" placeholder="e.g. 1" min="1" required>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light">To</span>
                                <input type="number" class="form-control" name="number_end" placeholder="e.g. 10" min="1" required>
                            </div>
                        </div>
                        <small class="text-muted">Specify the first and last seat numbers in each row</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="fas fa-couch me-2 text-primary"></i>Seat Properties</label>
                        <select class="form-select mb-2" name="type" required>
                            <option value="regular">Regular Seats</option>
                            <option value="premium">Premium Seats</option>
                            <option value="vip">VIP Seats</option>
                        </select>
                        
                        <div class="input-group mt-3">
                            <span class="input-group-text bg-light">$</span>
                            <input type="number" class="form-control" name="additional_charge" value="0.00" step="0.01" min="0" required placeholder="Additional Charge">
                        </div>
                        <small class="text-muted">Select seat type and set additional charge</small>
                        
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="is_available" value="1" checked>
                            <label class="form-check-label">Set all seats as available</label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-chairs me-2"></i> Create Multiple Seats
                        </button>
                    </div>
                </form>
                
                <div class="mt-4">
                    <div class="seat-example d-flex justify-content-center my-3">
                        <div class="hall-visual">
                            <div class="screen mb-3 bg-dark text-white text-center py-1 rounded">SCREEN</div>
                            <div class="row-example">
                                <span class="row-letter me-2">A</span>
                                <div class="seat"></div>
                                <div class="seat"></div>
                                <div class="seat"></div>
                                <div class="seat"></div>
                            </div>
                            <div class="row-example">
                                <span class="row-letter me-2">B</span>
                                <div class="seat"></div>
                                <div class="seat"></div>
                                <div class="seat"></div>
                                <div class="seat"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light">
                <div class="text-center">
                    <i class="fas fa-info-circle me-1 text-primary"></i>
                    <span class="small">The bulk creation tool generates all combinations in the ranges you specify.</span>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Seat Type Guide</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center">
                        <span class="badge bg-info me-3">Regular</span>
                        <div>
                            <strong>Standard seats</strong>
                            <div class="small text-muted">No additional charge</div>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <span class="badge bg-warning me-3">Premium</span>
                        <div>
                            <strong>Better positioned seats</strong>
                            <div class="small text-muted">Moderate additional charge</div>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <span class="badge bg-danger text-white me-3">VIP</span>
                        <div>
                            <strong>Best seats with extra comfort</strong>
                            <div class="small text-muted">Premium additional charge</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .hall-visual {
        max-width: 250px;
    }
    
    .screen {
        border-radius: 50px 50px 0 0;
        font-size: 0.8rem;
        letter-spacing: 1px;
    }
    
    .row-example {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    
    .row-letter {
        font-weight: bold;
        width: 20px;
    }
    
    .seat {
        width: 24px;
        height: 24px;
        background-color: #0dcaf0;
        margin: 0 3px;
        border-radius: 4px 4px 8px 8px;
        border: 1px solid rgba(0,0,0,0.2);
    }
    
    .border-primary-subtle {
        border-color: rgba(13, 110, 253, 0.3);
    }
</style>
@endsection 