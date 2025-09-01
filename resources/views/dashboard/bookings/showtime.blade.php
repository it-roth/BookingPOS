@extends('layouts.app')

@section('title', 'Select Showtime - Cinema Management')

@section('page-title', 'Select Showtime')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <!-- Progress Bar -->
            <div class="card-body border-bottom">
                <div class="booking-progress">
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="step-indicator completed" data-step="1">
                            <div class="step-circle">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="step-label">Movie</div>
                        </div>
                        <div class="step-indicator active" data-step="2">
                            <div class="step-circle">2</div>
                            <div class="step-label">Showtime</div>
                        </div>
                        <div class="step-indicator" data-step="3">
                            <div class="step-circle">3</div>
                            <div class="step-label">Seats</div>
                        </div>
                        <div class="step-indicator" data-step="4">
                            <div class="step-circle">4</div>
                            <div class="step-label">Food</div>
                        </div>
                        <div class="step-indicator" data-step="5">
                            <div class="step-circle">5</div>
                            <div class="step-label">Customer</div>
                        </div>
                        <div class="step-indicator" data-step="6">
                            <div class="step-circle">6</div>
                            <div class="step-label">Payment</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Selected Movie Summary -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        @if($movie->image)
                            <img src="{{ asset($movie->image) }}" alt="{{ $movie->title }}" class="img-fluid rounded">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-film fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h4 class="mb-2">{{ $movie->title }}</h4>
                        <p class="text-muted mb-2">
                            <i class="fas fa-clock me-1"></i> {{ $movie->duration }} min
                            <i class="fas fa-film ms-3 me-1"></i> {{ $movie->genre }}
                        </p>
                        <p class="mb-0">{{ $movie->description }}</p>
                    </div>
                </div>

                <!-- Showtime Selection -->
                <h5 class="mb-3">Available Showtimes</h5>
                <div class="row g-3">
                    @forelse($movieHalls as $movieHall)
                        <div class="col-md-4">
                            <div class="card showtime-card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $movieHall->hall->name }}</h6>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-chair me-1"></i> {{ $movieHall->hall->capacity }} seats
                                    </p>
                                    <div class="d-grid">
                                        <button type="button" class="btn btn-outline-primary select-showtime" 
                                                data-movie-hall-id="{{ $movieHall->id }}">
                                            {{ $movieHall->showtime->format('l, F j, Y') }}<br>
                                            <strong>{{ $movieHall->showtime->format('g:i A') }}</strong>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> No showtimes available for this movie.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('dashboard.bookings.create') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Movies
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .booking-progress {
        position: relative;
        padding: 0 2rem;
    }
    .step-indicator {
        text-align: center;
        position: relative;
        z-index: 1;
    }
    .step-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-weight: bold;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .step-indicator.active .step-circle {
        background-color: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }
    .step-indicator.completed .step-circle {
        background-color: var(--success);
        border-color: var(--success);
        color: #fff;
    }
    .step-label {
        font-size: 0.75rem;
        margin-top: 0.5rem;
        color: #6c757d;
    }
    .step-indicator.active .step-label {
        color: var(--primary);
        font-weight: 500;
    }
    .step-indicator.completed .step-label {
        color: var(--success);
        font-weight: 500;
    }
    .showtime-card {
        transition: transform 0.2s ease-in-out;
    }
    .showtime-card:hover {
        transform: translateY(-5px);
    }
    .select-showtime {
        padding: 1rem;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const showtimeButtons = document.querySelectorAll('.select-showtime');
    
    showtimeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const movieHallId = this.dataset.movieHallId;
            window.location.href = `/dashboard/bookings/create/seats/${movieHallId}`;
        });
    });
});
</script>
@endsection 