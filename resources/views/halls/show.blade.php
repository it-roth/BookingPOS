@extends('layouts.app')

@section('title', $hall->name . ' - Cinema Management')

@section('page-title', 'Hall Details')

@section('actions')
<div class="btn-group">
    <a href="{{ route('dashboard.halls') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Halls
    </a>
    <a href="{{ route('halls.edit', $hall->id) }}" class="btn btn-warning text-white">
        <i class="fas fa-edit me-1"></i> Edit
    </a>
    <form action="{{ route('halls.destroy', $hall->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this hall?')">
            <i class="fas fa-trash me-1"></i> Delete
        </button>
    </form>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Hall Overview</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                        <i class="fas fa-building fa-3x text-primary"></i>
                    </div>
                    <h4 class="mt-2">{{ $hall->name }}</h4>
                    <span class="badge bg-{{ $hall->is_active ? 'success' : 'danger' }}">
                        {{ $hall->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Hall Type</th>
                        <td>{{ $hall->hall_type }}</td>
                    </tr>
                    <tr>
                        <th>Capacity</th>
                        <td>{{ $hall->capacity }} seats</td>
                    </tr>
                    <tr>
                        <th>Seats Added</th>
                        <td>{{ $hall->seats->count() }} seats</td>
                    </tr>
                    <tr>
                        <th>Seats Available</th>
                        <td>{{ $hall->seats->where('is_available', true)->count() }} seats</td>
                    </tr>
                </table>
                
                @if($hall->description)
                    <div class="mt-3">
                        <h6>Description:</h6>
                        <p class="card-text">{{ $hall->description }}</p>
                    </div>
                @endif
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('seats.create') }}?hall_id={{ $hall->id }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Seats
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Now Playing in This Hall</h5>
            </div>
            <div class="card-body">
                @if($movieShowtimes->count() > 0)
                    <div class="row">
                        @foreach($movieShowtimes as $movieId => $showtimes)
                            @php
                                $movie = $showtimes->first()->movie;
                                $firstShowtime = $showtimes->first();
                            @endphp
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <div class="movie-poster-container h-100">
                                                @if($movie->image)
                                                    <img src="{{ asset($movie->image) }}" class="img-fluid rounded-start h-100" alt="{{ $movie->title }}" style="object-fit: cover;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                                        <i class="fas fa-film fa-3x text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $movie->title }}</h5>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $movie->duration }} min
                                                    </small>
                                                </p>
                                                <div class="showtimes-list">
                                                    <h6 class="mb-2">Showtimes:</h6>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($showtimes as $showtime)
                                                            <span class="badge bg-{{ $showtime->is_active ? 'success' : 'secondary' }}">
                                                                {{ $showtime->showtime->format('g:i A') }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No movies are currently playing in this hall.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Seat Management</h5>
                <div>
                    @if($hall->seats->count() > 0)
                        <a href="{{ route('seats.create') }}?hall_id={{ $hall->id }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Add More Seats
                        </a>
                    @else
                        <a href="{{ route('seats.create') }}?hall_id={{ $hall->id }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i> Create Initial Seats
                        </a>
                    @endif
                   
                </div>
            </div>
            <div class="card-body">
                @if($hall->seats->count() > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="seat-map-container">
                                <div class="text-center mb-4">
                                    <div class="screen bg-secondary text-white p-2 rounded mb-4" style="max-width: 70%; margin: 0 auto;">
                                        SCREEN
                                    </div>
                                    
                                    @php
                                        $seatsByRow = $hall->seats->sortBy('number')->groupBy('row');
                                        $rows = $seatsByRow->keys()->sort();
                                    @endphp
                                    
                                    @foreach($rows as $row)
                                        <div class="seat-row mb-2">
                                            <span class="row-label me-2">{{ $row }}</span>
                                            @foreach($seatsByRow[$row] as $seat)
                                                @php
                                                    $seatClass = 'bg-light';
                                                    if($seat->type == 'premium') {
                                                        $seatClass = 'bg-warning';
                                                    } elseif($seat->type == 'vip') {
                                                        $seatClass = 'bg-danger text-white';
                                                    }
                                                    
                                                    if(!$seat->is_available) {
                                                        $seatClass .= ' opacity-50';
                                                    }
                                                @endphp
                                                <a href="{{ route('seats.show', $seat->id) }}" class="seat {{ $seatClass }}" data-bs-toggle="tooltip" title="Row {{ $seat->row }}, Seat {{ $seat->number }} ({{ ucfirst($seat->type) }})">
                                                    {{ $seat->number }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="seat-legend d-flex justify-content-center flex-wrap">
                                    <div class="d-flex align-items-center me-3 mb-2">
                                        <div class="seat bg-light me-1" style="pointer-events: none;">A</div>
                                        <small>Regular</small>
                                    </div>
                                    <div class="d-flex align-items-center me-3 mb-2">
                                        <div class="seat bg-warning me-1" style="pointer-events: none;">A</div>
                                        <small>Premium</small>
                                    </div>
                                    <div class="d-flex align-items-center me-3 mb-2">
                                        <div class="seat bg-danger text-white me-1" style="pointer-events: none;">A</div>
                                        <small>VIP</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="seat bg-light opacity-50 me-1" style="pointer-events: none;">A</div>
                                        <small>Unavailable</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h1 class="display-4">{{ $hall->seats->count() }}</h1>
                                    <p class="text-muted">Total Seats</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6>Seat Types Distribution</h6>
                                    <div class="mt-3">
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span>Regular</span>
                                                <span>{{ $hall->seats->where('type', 'regular')->count() }}</span>
                                            </div>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-info" style="width: {{ $hall->seats->count() > 0 ? ($hall->seats->where('type', 'regular')->count() / $hall->seats->count() * 100) : 0 }}%"></div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span>Premium</span>
                                                <span>{{ $hall->seats->where('type', 'premium')->count() }}</span>
                                            </div>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-warning" style="width: {{ $hall->seats->count() > 0 ? ($hall->seats->where('type', 'premium')->count() / $hall->seats->count() * 100) : 0 }}%"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="d-flex justify-content-between">
                                                <span>VIP</span>
                                                <span>{{ $hall->seats->where('type', 'vip')->count() }}</span>
                                            </div>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-danger" style="width: {{ $hall->seats->count() > 0 ? ($hall->seats->where('type', 'vip')->count() / $hall->seats->count() * 100) : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> No seats have been created for this hall yet.
                    </div>
                    <div class="text-center mt-4">
                        <p>Create seats for this hall to start selling tickets.</p>
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('seats.create') }}?hall_id={{ $hall->id }}" class="btn btn-primary me-2">
                                <i class="fas fa-plus me-1"></i> Create Individual Seats
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .seat-row {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }
    
    .row-label {
        font-weight: bold;
        width: 30px;
        text-align: right;
    }
    
    .seat {
        display: inline-block;
        width: 35px;
        height: 35px;
        margin: 0 3px;
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        text-decoration: none;
        color: #333;
        border: 1px solid #ddd;
        transition: all 0.2s;
    }
    
    .seat:hover {
        transform: translateY(-3px);
        box-shadow: 0 3px 5px rgba(0,0,0,0.2);
    }
    
    .screen {
        display: block;
        width: 100%;
        border-radius: 5px;
        font-weight: bold;
        letter-spacing: 2px;
    }
    
    .seat-icon {
        background-color: #ccc;
        color: #333;
    }
    .seat-icon.regular {
        background-color: #0dcaf0;
        color: white;
    }
    .seat-icon.premium {
        background-color: #ffc107;
        color: #333;
    }
    .seat-icon.vip {
        background-color: #dc3545;
        color: white;
    }
    .seat-icon.unavailable {
        background-color: #6c757d;
        color: white;
        text-decoration: line-through;
    }
    .movie-poster-container {
        overflow: hidden;
        border-radius: 0.25rem 0 0 0.25rem;
    }
    .movie-poster-container img {
        transition: transform 0.3s ease;
    }
    .movie-poster-container:hover img {
        transform: scale(1.05);
    }
    .showtimes-list .badge {
        font-size: 0.85rem;
        padding: 0.5em 0.75em;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection 