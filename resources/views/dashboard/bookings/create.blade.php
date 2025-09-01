@extends('layouts.app')

@section('title', 'New Booking - Cinema Management')

@section('page-title', 'New Booking')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Step 1: Select Movie</h5>
                    <div class="d-flex align-items-center">
                        <form class="me-3" action="{{ route('dashboard.bookings.create') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search movies..." value="{{ request('search') }}">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @forelse($movies as $movie)
                    <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card movie-card h-100" data-movie-id="{{ $movie->id }}">
                            <div class="movie-poster position-relative" style="height: 400px;">
                                @if($movie->image)
                                    <img src="{{ asset($movie->image) }}" alt="{{ $movie->title }}" 
                                         class="w-100 h-100 object-fit-cover rounded-top">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100 rounded-top">
                                        <i class="fas fa-film fa-3x text-muted"></i>
                                    </div>
                                @endif
                                <div class="movie-duration position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-dark">
                                        <i class="fas fa-clock me-1"></i>{{ $movie->duration }} min
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-1">{{ $movie->title }}</h5>
                                <p class="text-muted small mb-2">{{ $movie->genre }}</p>
                                <p class="card-text small text-truncate">{{ $movie->description }}</p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <button type="button" class="btn btn-primary w-100 select-movie" data-movie-id="{{ $movie->id }}">
                                    <i class="fas fa-check-circle me-1"></i> Select Movie
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-film fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No movies found.</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $movies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .movie-card {
        transition: transform 0.2s ease-in-out;
        cursor: pointer;
    }
    .movie-card:hover {
        transform: translateY(-5px);
    }
    .movie-card.selected {
        border-color: var(--primary);
        box-shadow: 0 0 0 1px var(--primary);
    }
    .object-fit-cover {
        object-fit: cover;
    }
    .movie-duration {
        z-index: 1;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const movieCards = document.querySelectorAll('.movie-card');
    
    movieCards.forEach(card => {
        card.addEventListener('click', function() {
            const movieId = this.dataset.movieId;
            // Remove selected class from all cards
            movieCards.forEach(c => c.classList.remove('selected'));
            // Add selected class to clicked card
            this.classList.add('selected');
            
            // Navigate to showtime selection
            window.location.href = `/dashboard/bookings/create/showtimes/${movieId}`;
        });
    });
});
</script>
@endsection 