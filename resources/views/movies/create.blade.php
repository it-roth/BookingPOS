@extends('layouts.app')

@section('title', 'Add New Movie - Cinema Management')

@section('page-title', 'Add New Movie')

@section('actions')
<a href="{{ route('dashboard.movies') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back to Movies
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Movie Information</h5>
    </div>
    <div class="card-body">
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form action="{{ route('movies.store') }}" method="POST" enctype="multipart/form-data" id="createMovieForm">
            @csrf
            
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('genre') is-invalid @enderror" id="genre" name="genre" value="{{ old('genre') }}" required>
                    @error('genre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-md-4">
                    <label for="duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration') }}" min="1" required>
                    @error('duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <label for="release_date" class="form-label">Release Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('release_date') is-invalid @enderror" id="release_date" name="release_date" value="{{ old('release_date') }}" required>
                    @error('release_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-4">
                    <label for="poster" class="form-label">Movie Poster</label>
                    <input type="file" class="form-control @error('poster') is-invalid @enderror" id="poster" name="poster" accept="image/*">
                    <div class="form-text">Upload poster (JPG, PNG) - Max 2MB</div>
                    @error('poster')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Enter movie description...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input @error('is_showing') is-invalid @enderror" type="checkbox" id="is_showing" name="is_showing" value="1" {{ old('is_showing') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_showing">
                        Currently Showing
                    </label>
                    @error('is_showing')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                <a href="{{ route('dashboard.movies') }}" class="btn btn-outline-secondary order-2 order-sm-1">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary order-1 order-sm-2" id="submitMovie">
                    <i class="fas fa-save me-1"></i> Save Movie
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('createMovieForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitMovie');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
});
</script>
@endpush
@endsection 