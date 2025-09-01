@extends('layouts.app')

@section('title', 'Edit Movie - Cinema Management')

@section('page-title', 'Edit Movie')

@section('actions')
<a href="{{ route('dashboard.movies') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back to Movies
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Movie Information</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $movie->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('genre') is-invalid @enderror" id="genre" name="genre" value="{{ old('genre', $movie->genre) }}" required>
                        @error('genre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $movie->duration) }}" min="1" required>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="release_date" class="form-label">Release Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('release_date') is-invalid @enderror" id="release_date" name="release_date" value="{{ old('release_date', $movie->release_date->format('Y-m-d')) }}" required>
                        @error('release_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="poster" class="form-label">Movie Poster</label>
                        <input type="file" class="form-control @error('poster') is-invalid @enderror" id="poster" name="poster" accept="image/*">
                        <div class="form-text">Upload new poster image (JPG, PNG) - Max 2MB</div>
                        @error('poster')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        @if($movie->image)
                        <div class="mt-2">
                            <label>Current Poster:</label>
                            <div class="mt-1">
                                <img src="{{ asset($movie->image) }}" alt="{{ $movie->title }}" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $movie->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input @error('is_showing') is-invalid @enderror" type="checkbox" value="1" id="is_showing" name="is_showing" {{ old('is_showing', $movie->is_showing) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_showing">
                        Currently Showing
                    </label>
                    @error('is_showing')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Movie
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 