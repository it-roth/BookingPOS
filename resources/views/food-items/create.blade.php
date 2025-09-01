@extends('layouts.app')

@section('title', 'Add New Food Item - Cinema Management')

@section('page-title', 'Add New Food Item')

@section('actions')
<a href="{{ route('dashboard.food-items') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back to Food Items
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Food Item Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('food-items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Snacks" {{ old('category') == 'Snacks' ? 'selected' : '' }}>Snacks</option>
                                    <option value="Fast Food" {{ old('category') == 'Fast Food' ? 'selected' : '' }}>Fast Food</option>
                                    <option value="Desserts" {{ old('category') == 'Desserts' ? 'selected' : '' }}>Desserts</option>
                                    <option value="Combos" {{ old('category') == 'Combos' ? 'selected' : '' }}>Combo Meals</option>
                                    <option value="Kids Meals" {{ old('category') == 'Kids Meals' ? 'selected' : '' }}>Kids Meals</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="preparation_time" class="form-label">Preparation Time (minutes)</label>
                                <input type="number" class="form-control @error('preparation_time') is-invalid @enderror" id="preparation_time" name="preparation_time" value="{{ old('preparation_time') }}" min="0">
                                @error('preparation_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Food Item Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <div class="form-text">Upload food item image (JPG, PNG) - Max 2MB</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input @error('is_available') is-invalid @enderror" type="checkbox" role="switch" id="is_available" name="is_available" value="1" {{ old('is_available', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">Available for Purchase</label>
                            @error('is_available')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Food Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Food Item Preview</h5>
            </div>
            <div class="card-body text-center">
                <div class="preview-container mb-3">
                    <div id="image-preview" class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 200px; height: 200px; margin: 0 auto;">
                        <i class="fas fa-hamburger fa-4x text-secondary"></i>
                    </div>
                </div>
                
                <h4 id="name-preview">New Food Item</h4>
                <span id="category-preview" class="badge bg-primary mb-2">Category</span>
                <h5 id="price-preview" class="text-success">$0.00</h5>
                
                <p id="notes-preview" class="text-muted mt-3">Item description will appear here...</p>
                
                <div class="mt-3 text-start">
                    <div class="d-flex justify-content-between">
                        <span><i class="fas fa-clock me-2 text-info"></i> Preparation time:</span>
                        <span id="prep-time-preview">N/A</span>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span><i class="fas fa-check-circle me-2 text-success"></i> Status:</span>
                        <span id="status-preview" class="badge bg-success">Available</span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-center text-muted small">
                    This preview updates as you fill out the form.
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
        const priceInput = document.getElementById('price');
        const prepTimeInput = document.getElementById('preparation_time');
        const imageInput = document.getElementById('image');
        const isAvailableCheck = document.getElementById('is_available');
        
        // Preview elements
        const namePreview = document.getElementById('name-preview');
        const categoryPreview = document.getElementById('category-preview');
        const pricePreview = document.getElementById('price-preview');
        const prepTimePreview = document.getElementById('prep-time-preview');
        const imagePreview = document.getElementById('image-preview');
        const statusPreview = document.getElementById('status-preview');
        
        // Update preview when inputs change
        nameInput.addEventListener('input', function() {
            namePreview.textContent = this.value || 'New Food Item';
        });
        
        categorySelect.addEventListener('change', function() {
            categoryPreview.textContent = this.options[this.selectedIndex].text;
        });
        
        priceInput.addEventListener('input', function() {
            pricePreview.textContent = '$' + (parseFloat(this.value) || 0).toFixed(2);
        });
        
        prepTimeInput.addEventListener('input', function() {
            prepTimePreview.textContent = this.value ? this.value + ' min' : 'N/A';
        });
        
        imageInput.addEventListener('change', function() {
            console.log('Image changed', this.files);
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" alt="Food item" class="img-fluid rounded" style="max-height: 200px; max-width: 100%;">`;
                }
                
                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreview.innerHTML = '<i class="fas fa-hamburger fa-4x text-secondary"></i>';
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