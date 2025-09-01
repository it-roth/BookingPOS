@extends('layouts.app')

@section('title', 'Edit Drink - Cinema Management')

@section('page-title', 'Edit Drink')

@section('actions')
<a href="{{ route('dashboard.drinks') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back to Drinks
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Drink: {{ $drink->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('drinks.update', $drink->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $drink->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Soft Drinks" {{ old('category', $drink->category) == 'Soft Drinks' ? 'selected' : '' }}>Soft Drinks</option>
                                <option value="Juices" {{ old('category', $drink->category) == 'Juices' ? 'selected' : '' }}>Juices</option>
                                <option value="Hot Beverages" {{ old('category', $drink->category) == 'Hot Beverages' ? 'selected' : '' }}>Hot Beverages</option>
                                <option value="Water" {{ old('category', $drink->category) == 'Water' ? 'selected' : '' }}>Water</option>
                                <option value="Alcoholic" {{ old('category', $drink->category) == 'Alcoholic' ? 'selected' : '' }}>Alcoholic Beverages</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $drink->price) }}" step="0.01" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="size" class="form-label">Size <span class="text-danger">*</span></label>
                            <select class="form-select @error('size') is-invalid @enderror" id="size" name="size" required>
                                <option value="">Select Size</option>
                                <option value="small" {{ old('size', $drink->size) == 'small' ? 'selected' : '' }}>Small</option>
                                <option value="regular" {{ old('size', $drink->size) == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="large" {{ old('size', $drink->size) == 'large' ? 'selected' : '' }}>Large</option>
                            </select>
                            @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('is_available') is-invalid @enderror" type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', $drink->is_available) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">
                                Available for Sale
                            </label>
                            @error('is_available')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="image" class="form-label">Drink Image</label>
                        
                        <div class="preview-container mb-3">
                            <div id="image-preview" class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 200px; height: 200px; margin: 0 auto;">
                                @if($drink->image)
                                    <img src="{{ asset($drink->image) }}" alt="{{ $drink->name }}" class="img-fluid rounded" style="max-height: 200px; max-width: 100%;">
                                @else
                                    <i class="fas fa-coffee fa-4x text-secondary"></i>
                                @endif
                            </div>
                        </div>
                        
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <div class="form-text">Upload a new image to replace the current one. Leave blank to keep the existing image.</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard.drinks') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Drink
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Drink Preview</h5>
            </div>
            <div class="card-body text-center">
                <h4 id="name-preview">{{ $drink->name }}</h4>
                <div class="mb-2">
                    <span id="category-preview" class="badge bg-primary me-1">{{ $drink->category }}</span>
                    <span id="size-preview" class="badge bg-secondary">{{ ucfirst($drink->size) }}</span>
                </div>
                <h5 id="price-preview" class="text-success">${{ number_format($drink->price, 2) }}</h5>
                
                <div class="mt-3 text-start">
                    <div class="d-flex justify-content-between mt-2">
                        <span><i class="fas fa-check-circle me-2 text-success"></i> Status:</span>
                        <span id="status-preview" class="badge {{ $drink->is_available ? 'bg-success' : 'bg-danger' }}">
                            {{ $drink->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preview functionality
        const nameInput = document.getElementById('name');
        const categorySelect = document.getElementById('category');
        const sizeSelect = document.getElementById('size');
        const priceInput = document.getElementById('price');
        const imageInput = document.getElementById('image');
        const isAvailableCheck = document.getElementById('is_available');
        
        // Preview elements
        const namePreview = document.getElementById('name-preview');
        const categoryPreview = document.getElementById('category-preview');
        const sizePreview = document.getElementById('size-preview');
        const pricePreview = document.getElementById('price-preview');
        const imagePreview = document.getElementById('image-preview');
        const statusPreview = document.getElementById('status-preview');
        
        // Update preview when inputs change
        nameInput.addEventListener('input', function() {
            namePreview.textContent = this.value || 'Edit Drink';
        });
        
        categorySelect.addEventListener('change', function() {
            categoryPreview.textContent = this.options[this.selectedIndex].text;
        });
        
        sizeSelect.addEventListener('change', function() {
            sizePreview.textContent = this.options[this.selectedIndex].text;
        });
        
        priceInput.addEventListener('input', function() {
            pricePreview.textContent = '$' + (parseFloat(this.value) || 0).toFixed(2);
        });
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" alt="Drink" class="img-fluid rounded" style="max-height: 200px; max-width: 100%;">`;
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        isAvailableCheck.addEventListener('change', function() {
            if (this.checked) {
                statusPreview.className = 'badge bg-success';
                statusPreview.textContent = 'Available';
            } else {
                statusPreview.className = 'badge bg-danger';
                statusPreview.textContent = 'Unavailable';
            }
        });
    });
</script>
@endsection 