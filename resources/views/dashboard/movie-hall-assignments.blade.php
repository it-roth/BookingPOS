@extends('layouts.app')

@section('title', 'Movie-Hall')

@section('page-title', 'Movie-Hall ')

@section('actions')
    <a href="{{ route('dashboard.movies') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-film me-1"></i> Movies
    </a>
    <a href="{{ route('dashboard.halls') }}" class="btn btn-primary btn-sm ms-2">
        <i class="fas fa-building me-1"></i> Halls
    </a>
@endsection

@section('content')
<style>
    .highlighted-row {
        background-color: rgba(67, 97, 238, 0.1);
        animation: highlight-fade 3s ease-out;
    }
    
    @keyframes highlight-fade {
        from { background-color: rgba(67, 97, 238, 0.3); }
        to { background-color: rgba(67, 97, 238, 0.1); }
    }
    
    .showtimes-badge {
        transition: all 0.2s ease;
    }
    
    .showtimes-badge:hover {
        transform: scale(1.1);
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Current Movie-Hall Assignments</h5>
                    <div>
                        @if(isset($highlightedMovie))
                            <span class="badge bg-primary me-2">Viewing showtimes for selected movie</span>
                        @endif
                        <a href="{{ route('dashboard.pos') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-cash-register me-1"></i> POS System
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($assignments) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Movie</th>
                                        <th>Assigned Halls</th>
                                        <th>Upcoming Showtimes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignments as $assignment)
                                        <tr class="{{ isset($highlightedMovie) && $highlightedMovie == $assignment['movie']->id ? 'highlighted-row' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($assignment['movie']->image)
                                                        <img src="{{ asset($assignment['movie']->image) }}" alt="{{ $assignment['movie']->title }}" 
                                                            class="me-3" style="width: 40px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                                            style="width: 40px; height: 60px;">
                                                            <i class="fas fa-film text-secondary"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $assignment['movie']->title }}</strong>
                                                        <div class="small text-muted">
                                                            {{ $assignment['movie']->duration }} min | {{ $assignment['movie']->genre }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @foreach($assignment['halls'] as $hall)
                                                    <span class="badge bg-primary mb-1 me-1">{{ $hall->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if(count($assignment['showtimes']) > 0)
                                                    @foreach($assignment['halls'] as $hall)
                                                        @if(isset($assignment['showtimes'][$hall->id]) && $assignment['showtimes'][$hall->id]->count() > 0)
                                                            <div class="mb-2">
                                                                <strong>{{ $hall->name }}:</strong>
                                                                <div>
                                                                    @foreach($assignment['showtimes'][$hall->id] as $showtime)
                                                                        <span class="badge bg-info me-1 showtimes-badge">
                                                                            {{ $showtime->showtime->format('M d, g:i A') }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No upcoming showtimes</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No movie-hall assignments found. Please run the MovieHallSeeder to generate assignments.
                        </div>
                        <div class="text-center mt-3">
                            <p>To run the seeder, execute this command:</p>
                            <pre class="bg-light p-3 rounded">php artisan db:seed --class=MovieHallSeeder</pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Available Movies</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movies as $movie)
                                    <tr>
                                        <td>{{ $movie->id }}</td>
                                        <td>{{ $movie->title }}</td>
                                        <td>
                                            @if($movie->is_showing)
                                                <span class="badge bg-success">Showing</span>
                                            @else
                                                <span class="badge bg-secondary">Not Showing</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Available Halls</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Capacity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($halls as $hall)
                                    <tr>
                                        <td>{{ $hall->id }}</td>
                                        <td>{{ $hall->name }}</td>
                                        <td>{{ $hall->hall_type }}</td>
                                        <td>{{ $hall->capacity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
 