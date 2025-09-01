@extends('layouts.app')

@section('title', 'Bulk Add Seats - Cinema Management')

@section('page-title', 'Bulk Add Seats')

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
                <h5 class="mb-0">Bulk Seat Creation</h5>
            </div>
            <div class="card-body">
                @if ($selectedHall)
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i> You are adding seats to <strong>{{ $selectedHall->name }}</strong> ({{ $selectedHall->hall_type }})
                    </div>
                @endif
                
                @if (session('debug'))
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-bug me-2"></i> Debug: {{ session('debug') }}
                    </div>
                @endif
                
                <form action="{{ route('seats.bulkStore') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="hall_id" class="form-label">Hall <span class="text-danger">*</span></label>
                                <select class="form-select @error('hall_id') is-invalid @enderror" id="hall_id" name="hall_id" required>
                                    <option value="">Select Hall</option>
                                    @foreach($halls as $hall)
                                        <option value="{{ $hall->id }}" {{ old('hall_id', $selectedHall ? $selectedHall->id : '') == $hall->id ? 'selected' : '' }}>
                                            {{ $hall->name }} ({{ $hall->hall_type }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('hall_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">Row Range</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="row_start" class="form-label">Start Row <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('row_start') is-invalid @enderror" id="row_start" name="row_start" value="{{ old('row_start', $rowStart) }}" required maxlength="2">
                                        @error('row_start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="row_end" class="form-label">End Row <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('row_end') is-invalid @enderror" id="row_end" name="row_end" value="{{ old('row_end', $rowEnd) }}" required maxlength="2">
                                        @error('row_end')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-text mb-3">Example: A to E or 1 to 5</div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="mb-3">Seat Number Range</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="number_start" class="form-label">Start Number <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('number_start') is-invalid @enderror" id="number_start" name="number_start" value="{{ old('number_start', $numberStart) }}" required min="1">
                                        @error('number_start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="number_end" class="form-label">End Number <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('number_end') is-invalid @enderror" id="number_end" name="number_end" value="{{ old('number_end', $numberEnd) }}" required min="1">
                                        @error('number_end')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-text mb-3">Example: 1 to 10</div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Seat Type for All <span class="text-danger">*</span></label>
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
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="additional_charge" class="form-label">Additional Charge for All <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('additional_charge') is-invalid @enderror" id="additional_charge" name="additional_charge" value="{{ old('additional_charge', '0.00') }}" step="0.01" min="0" required>
                                    @error('additional_charge')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input @error('is_available') is-invalid @enderror" type="checkbox" role="switch" id="is_available" name="is_available" value="1" {{ old('is_available', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">Set all seats as available</label>
                            @error('is_available')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> 
                        <strong>Warning:</strong> This action will create multiple seats at once. Any existing seats with the same row and number will be skipped.
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Create Seats
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Seats Preview</h5>
            </div>
            <div class="card-body">
                <div id="seats-preview" class="text-center p-3">
                    <div class="alert alert-secondary">
                        Fill in the form to see a preview
                    </div>
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
                        <i class="fas fa-check-circle text-success me-2"></i> 
                        Rows can be letters (A-Z) or numbers (1-99)
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check-circle text-success me-2"></i> 
                        Seat numbers should be consecutive integers
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check-circle text-success me-2"></i> 
                        VIP seats typically have the highest additional charge
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check-circle text-success me-2"></i> 
                        Additional charges are added to the base ticket price
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check-circle text-success me-2"></i> 
                        Already existing seats will be skipped during creation
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowStartInput = document.getElementById('row_start');
        const rowEndInput = document.getElementById('row_end');
        const numberStartInput = document.getElementById('number_start');
        const numberEndInput = document.getElementById('number_end');
        const typeSelect = document.getElementById('type');
        const previewDiv = document.getElementById('seats-preview');
        
        function updatePreview() {
            let rowStart = rowStartInput.value.toUpperCase();
            let rowEnd = rowEndInput.value.toUpperCase();
            let numberStart = parseInt(numberStartInput.value) || 1;
            let numberEnd = parseInt(numberEndInput.value) || numberStart;
            let seatType = typeSelect.value;
            
            if (!rowStart || !rowEnd || !numberStart || !numberEnd) {
                previewDiv.innerHTML = '<div class="alert alert-secondary">Fill in the form to see a preview</div>';
                return;
            }
            
            // Calculate rows
            let rows = [];
            
            // Handle numeric rows
            if (!isNaN(rowStart) && !isNaN(rowEnd)) {
                for (let i = parseInt(rowStart); i <= parseInt(rowEnd); i++) {
                    rows.push(i.toString());
                }
            } 
            // Handle alphabetic rows
            else {
                let startOrd = rowStart.charCodeAt(0);
                let endOrd = rowEnd.charCodeAt(0);
                
                if (startOrd <= endOrd) {
                    for (let i = startOrd; i <= endOrd; i++) {
                        rows.push(String.fromCharCode(i));
                    }
                }
            }
            
            // Limit preview for performance
            const maxPreviewRows = 6;
            const maxPreviewCols = 10;
            let rowsLimited = rows.length > maxPreviewRows;
            let colsLimited = (numberEnd - numberStart + 1) > maxPreviewCols;
            
            // Trim rows and columns for preview if needed
            let previewRows = rowsLimited ? rows.slice(0, maxPreviewRows) : rows;
            let previewNumberStart = numberStart;
            let previewNumberEnd = colsLimited ? numberStart + maxPreviewCols - 1 : numberEnd;
            
            // Build preview HTML
            let previewHTML = '<div class="screen-indicator mb-4 bg-secondary text-white py-2 rounded">SCREEN</div>';
            
            if (rows.length === 0) {
                previewHTML += '<div class="alert alert-warning">Invalid row range</div>';
            } else {
                previewHTML += '<div class="seat-grid">';
                
                // Add row labels and seats
                for (let row of previewRows) {
                    previewHTML += `<div class="d-flex justify-content-center mb-2">
                        <div class="me-3 d-flex align-items-center"><strong>${row}</strong></div>`;
                    
                    for (let num = previewNumberStart; num <= previewNumberEnd; num++) {
                        let seatClass = '';
                        switch(seatType) {
                            case 'regular': seatClass = 'bg-info'; break;
                            case 'premium': seatClass = 'bg-warning'; break;
                            case 'vip': seatClass = 'bg-danger text-white'; break;
                        }
                        
                        previewHTML += `<div class="seat-icon ${seatClass}" style="width: 30px; height: 30px; border-radius: 5px 5px 10px 10px; display: flex; align-items: center; justify-content: center; font-size: 12px; margin: 0 2px;">${num}</div>`;
                    }
                    
                    previewHTML += '</div>';
                }
                
                previewHTML += '</div>';
                
                // Add limitations note
                if (rowsLimited || colsLimited) {
                    previewHTML += '<div class="text-muted small mt-3"><i class="fas fa-info-circle me-1"></i> Preview limited. Actual creation will include all specified rows and seats.</div>';
                }
                
                // Add count
                const totalSeats = rows.length * (numberEnd - numberStart + 1);
                previewHTML += `<div class="mt-3 pt-2 border-top">
                    <span class="badge bg-primary">${totalSeats} seats will be created</span>
                </div>`;
            }
            
            previewDiv.innerHTML = previewHTML;
        }
        
        // Update preview on input changes
        rowStartInput.addEventListener('input', updatePreview);
        rowEndInput.addEventListener('input', updatePreview);
        numberStartInput.addEventListener('input', updatePreview);
        numberEndInput.addEventListener('input', updatePreview);
        typeSelect.addEventListener('change', updatePreview);
        
        // Initialize
        updatePreview();
    });
</script>
@endsection 