@extends('layouts.app')

@section('title', $movie->title . ' - Cinema Management')

@section('page-title', 'Movie Details')

@section('actions')
<div class="btn-group">
    <a href="{{ route('dashboard.movies') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Movies
    </a>
    <a href="{{ route('movies.edit', $movie->id) }}" class="btn btn-warning text-white">
        <i class="fas fa-edit me-1"></i> Edit
    </a>
    <form action="{{ route('movies.destroy', $movie->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this movie?')">
            <i class="fas fa-trash me-1"></i> Delete
        </button>
    </form>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                @if($movie->image)
                    <img src="{{ asset($movie->image) }}" alt="{{ $movie->title }}" class="img-fluid rounded mb-3" style="max-height: 300px;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 300px;">
                        <i class="fas fa-film fa-5x text-secondary"></i>
                    </div>
                @endif
                
                <h4 class="card-title">{{ $movie->title }}</h4>
                <p class="badge bg-primary">{{ $movie->genre }}</p>
                
                <div class="d-flex justify-content-around mt-3">
                    <div>
                        <i class="fas fa-clock text-info"></i>
                        <p class="mb-0">{{ $movie->duration }} min</p>
                    </div>
                    <div>
                        <i class="fas fa-calendar-alt text-warning"></i>
                        <p class="mb-0">{{ $movie->release_date->format('M d, Y') }}</p>
                    </div>
                </div>
                
                <div class="mt-3">
                    @if($movie->is_showing)
                        <span class="badge bg-success">Currently Showing</span>
                    @else
                        <span class="badge bg-secondary">Not Showing</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Movie Information</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">Description</h5>
                <p class="card-text">{{ $movie->description ?: 'No description available.' }}</p>
                
                <hr>
                
                <h5 class="card-title">Details</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="150">Title</th>
                            <td>{{ $movie->title }}</td>
                        </tr>
                        <tr>
                            <th>Genre</th>
                            <td>{{ $movie->genre }}</td>
                        </tr>
                        <tr>
                            <th>Duration</th>
                            <td>{{ $movie->duration }} minutes</td>
                        </tr>
                        <tr>
                            <th>Release Date</th>
                            <td>{{ $movie->release_date->format('F d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $movie->is_showing ? 'Currently Showing' : 'Not Showing' }}</td>
                        </tr>
                    </tbody>
                </table>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <span class="text-muted">
                        <small>Created: {{ $movie->created_at->format('M d, Y H:i') }}</small>
                    </span>
                    <span class="text-muted">
                        <small>Last Updated: {{ $movie->updated_at->format('M d, Y H:i') }}</small>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Playing in Halls</h5>
                <a href="{{ route('dashboard.movieHallAssignments') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-calendar-alt me-1"></i> View All Showtimes
                </a>
            </div>
            <div class="card-body">
                @if($hallShowtimes->count() > 0)
                    <div class="row">
                        @foreach($hallShowtimes as $hallId => $showtimes)
                            @php 
                                $hall = $showtimes->first()->hall;
                            @endphp
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">
                                                <i class="fas fa-building me-2"></i> {{ $hall->name }}
                                            </h6>
                                            <span class="badge bg-{{ $hall->is_active ? 'success' : 'danger' }}">
                                                {{ $hall->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2">
                                            <strong>Hall Type:</strong> {{ $hall->hall_type }}
                                        </p>
                                        <p class="mb-3">
                                            <strong>Capacity:</strong> {{ $hall->capacity }} seats
                                        </p>
                                        
                                        <h6 class="mb-2">Upcoming Showtimes:</h6>
                                        <div class="d-flex flex-wrap">
                                            @foreach($showtimes->take(5) as $showtime)
                                                <div class="showtime-chip me-2 mb-2 bg-light p-2 rounded border">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-clock text-primary me-1"></i>
                                                        <span>{{ $showtime->showtime->format('M d, g:i A') }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <div class="mt-3">
                                            <a href="{{ route('halls.show', $hall->id) }}" class="btn btn-sm btn-outline-primary">
                                                View Hall Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> This movie is not currently playing in any hall.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 