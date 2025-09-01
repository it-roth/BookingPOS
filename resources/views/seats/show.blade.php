@extends('layouts.app')

@section('title', 'Seat Details - Cinema Management')

@section('page-title', 'Seat Details')

@section('actions')
<div class="btn-group">
    <a href="{{ route('halls.show', $seat->hall_id) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Hall
    </a>
    <a href="{{ route('seats.edit', $seat->id) }}" class="btn btn-warning text-white">
        <i class="fas fa-edit me-1"></i> Edit
    </a>
    <form action="{{ route('seats.destroy', $seat->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this seat?')">
            <i class="fas fa-trash me-1"></i> Delete
        </button>
    </form>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Seat Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-chair fa-2x text-primary"></i>
                    </div>
                    <h4 class="mt-2">Row {{ $seat->row }}, Seat {{ $seat->number }}</h4>
                    <span class="badge bg-{{ $seat->is_available ? 'success' : 'danger' }}">
                        {{ $seat->is_available ? 'Available' : 'Unavailable' }}
                    </span>
                    <span class="badge bg-{{ $seat->type == 'regular' ? 'info' : ($seat->type == 'premium' ? 'warning' : 'danger') }} ms-1">
                        {{ ucfirst($seat->type) }}
                    </span>
                </div>
                
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Hall</th>
                        <td>
                            <a href="{{ route('halls.show', $seat->hall_id) }}">
                                {{ $seat->hall->name }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Row</th>
                        <td>{{ $seat->row }}</td>
                    </tr>
                    <tr>
                        <th>Number</th>
                        <td>{{ $seat->number }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ ucfirst($seat->type) }}</td>
                    </tr>
                    <tr>
                        <th>Additional Charge</th>
                        <td>${{ number_format($seat->additional_charge, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $seat->is_available ? 'success' : 'danger' }}">
                                {{ $seat->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Upcoming Showtimes in {{ $seat->hall->name }}</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($upcomingShowtimes) && $upcomingShowtimes->count() > 0)
                            <div class="list-group">
                                @foreach($upcomingShowtimes as $movieHall)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <div class="movie-poster bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 90px; overflow: hidden;">
                                            @if($movieHall->movie->poster_url)
                                                <img src="{{ asset($movieHall->movie->poster_url) }}" alt="{{ $movieHall->movie->title }}" class="h-100 w-100 object-fit-cover">
                                            @else
                                                <i class="fas fa-film fa-2x text-muted"></i>
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-1">{{ $movieHall->movie->title }}</h6>
                                            <p class="mb-1 small">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $movieHall->showtime->format('M d, Y - g:i A') }}
                                            </p>
                                            <p class="mb-0 small">
                                                <span class="badge bg-light text-dark border">{{ $movieHall->movie->duration }} min</span>
                                                <span class="badge bg-light text-dark border">{{ $movieHall->movie->genre }}</span>
                                                <span class="badge bg-light text-dark border">{{ $movieHall->movie->rating }}</span>
                                            </p>
                                        </div>
                                        <div class="ms-auto">
                                            <a href="{{ route('movies.show', $movieHall->movie_id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-info-circle me-1"></i> Movie Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> No upcoming showtimes for this hall.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Booking History for This Seat</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($bookingHistory) && $bookingHistory->count() > 0)
                            <div class="list-group">
                                @foreach($bookingHistory as $bookingItem)
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                {{ $bookingItem->booking->movieHall->movie->title }}
                                                <span class="badge bg-secondary">Booking #{{ $bookingItem->booking->booking_number }}</span>
                                            </h6>
                                            <p class="mb-1 small">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                Showtime: {{ $bookingItem->booking->movieHall->showtime->format('M d, Y - g:i A') }}
                                            </p>
                                            <p class="mb-0 small text-muted">
                                                <i class="far fa-user me-1"></i>
                                                {{ $bookingItem->booking->customer_name }} 
                                                <i class="far fa-clock ms-2 me-1"></i>
                                                Booked: {{ $bookingItem->created_at->format('M d, Y - g:i A') }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="badge bg-success">
                                                ${{ number_format($bookingItem->price, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> No booking history found for this seat.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 