@extends('layouts.app')

<link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
<link rel="shortcut icon" type="image/png" href="{{ asset('images/Logo.png') }}">
<meta name="user-profile-image" content="{{ asset('images/Logo.png') }}">
@section('title', 'Movies Dashboard - Cinema Management')

@section('page-title', 'Movies')

@section('actions')
<a href="{{ route('movies.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i> Add New Movie
</a>
@endsection

@section('content')
<style>
    /* Card and table styling */
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }
    
    /* Pagination styling */
    .page-link {
        color: #4361ee;
        border-radius: 0.25rem;
        margin: 0 2px;
    }
    .page-item.active .page-link {
        background-color: #4361ee;
        border-color: #4361ee;
    }
    .pagination-info {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    /* Loading overlay */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    
    /* Button styles */
    .btn-group .btn {
        margin-right: 2px;
        border-radius: 0.25rem !important;
        border-width: 1px;
        box-shadow: none !important;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-1px);
        transition: transform 0.2s ease;
    }
    
    .badge {
        padding: 0.5em 0.8em;
        border-radius: 30px;
    }
    
    .movie-poster {
        width: 50px; 
        height: 70px; 
        border-radius: 4px; 
        overflow: hidden;
        transition: all 0.2s ease;
    }
    
    .movie-poster:hover {
        transform: scale(1.05);
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="fas fa-film me-2"></i>Movies List
                </h5>
                <div class="d-flex">
                    <div class="dropdown me-2">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="movieStatusFilter" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(request()->has('is_showing'))
                                @if(request('is_showing') == 'true')
                                    Now Showing
                                @else
                                    Not Showing
                                @endif
                            @else
                                All Status
                            @endif
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="movieStatusFilter">
                            <li><a class="dropdown-item" href="{{ route('dashboard.movies') }}">All Status</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard.movies', ['is_showing' => 'true']) }}">Now Showing</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard.movies', ['is_showing' => 'false']) }}">Not Showing</a></li>
                        </ul>
                    </div>
                    <a href="{{ route('movies.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus me-1"></i> New Movie
                    </a>
                </div>
            </div>
            <div class="card-body p-0 position-relative">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Movie</th>
                                <th class="d-none d-md-table-cell">Genre</th>
                                <th class="d-none d-lg-table-cell">Duration</th>
                                <th>Status</th>
                                <th class="d-none d-sm-table-cell">Release</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="movies-table-body">
                            @forelse($movies as $movie)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="movie-poster bg-light d-flex align-items-center justify-content-center me-2 me-md-3" style="width: 40px; height: 40px; border-radius: 6px; overflow: hidden;">
                                            @if($movie->image)
                                                <img src="{{ asset(trim($movie->image, '/')) }}" alt="{{ $movie->title }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('images/default-movie.png') }}';">
                                            @else
                                                <img src="{{ asset('images/default-movie.png') }}" alt="Default Movie" style="width: 100%; height: 100%; object-fit: cover;">
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">{{ $movie->title }}</h6>
                                            <small class="text-muted">{{ $movie->rating ?? 'Not Rated' }}</small>
                                            <div class="d-md-none">
                                                <small class="badge bg-secondary me-1">{{ $movie->genre }}</small>
                                                <small class="text-muted">{{ $movie->duration }}min</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell"><span class="badge bg-secondary">{{ $movie->genre }}</span></td>
                                <td class="d-none d-lg-table-cell">{{ $movie->duration }} min</td>
                                <td>
                                    @if($movie->is_showing)
                                        <span class="badge bg-success">
                                            <span class="d-none d-sm-inline">Now Showing</span>
                                            <span class="d-sm-none">Showing</span>
                                        </span>
                                    @elseif($movie->release_date > now())
                                        <span class="badge bg-primary">
                                            <span class="d-none d-sm-inline">Coming Soon</span>
                                            <span class="d-sm-none">Soon</span>
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <span class="d-none d-sm-inline">Not Showing</span>
                                            <span class="d-sm-none">Off</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <small>{{ $movie->release_date->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group d-none d-md-flex">
                                        <a href="{{ route('movies.show', $movie->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('movies.edit', $movie->id) }}" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#showtimeModal" data-movie-id="{{ $movie->id }}" data-movie-title="{{ $movie->title }}" title="Connect Showtimes">
                                            <i class="fas fa-clock"></i>
                                        </a>
                                        <button type="submit" form="delete-form-{{ $movie->id }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this movie?')" data-bs-toggle="tooltip" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Mobile dropdown -->
                                    <div class="dropdown d-md-none">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('movies.show', $movie->id) }}"><i class="fas fa-eye me-2"></i>View</a></li>
                                            <li><a class="dropdown-item" href="{{ route('movies.edit', $movie->id) }}"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#showtimeModal" data-movie-id="{{ $movie->id }}" data-movie-title="{{ $movie->title }}"><i class="fas fa-clock me-2"></i>Showtimes</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><button class="dropdown-item text-danger" type="submit" form="delete-form-{{ $movie->id }}" onclick="return confirm('Are you sure you want to delete this movie?')"><i class="fas fa-trash me-2"></i>Delete</button></li>
                                        </ul>
                                    </div>

                                    <form id="delete-form-{{ $movie->id }}" action="{{ route('movies.destroy', $movie->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-film fa-3x text-muted mb-3"></i>
                                        <h5>No movies found</h5>
                                        <p class="text-muted">Get started by adding your first movie</p>
                                        <a href="{{ route('movies.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus me-1"></i> Add New Movie
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="pagination-info">
                        Showing {{ $movies->firstItem() ?? 0 }} to {{ $movies->lastItem() ?? 0 }} of {{ $movies->total() }} results
                    </div>
                    <div id="pagination-links">
                        {{ $movies->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Movies by Genre</h5>
            </div>
            <div class="card-body" style="height: 300px;">
                <canvas id="genreChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Movie Status</h5>
            </div>
            <div class="card-body" style="height: 300px;">
                <canvas id="statusChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-3">Total Movies</h6>
                                <h2 class="mb-0">{{ $movies->total() }}</h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6 mb-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-3">Now Showing</h6>
                                <h2 class="mb-0">{{ \App\Models\Movie::where('is_showing', true)->count() }}</h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-3">Coming Soon</h6>
                                <h2 class="mb-0">{{ \App\Models\Movie::where('is_showing', false)->where('release_date', '>', now())->count() }}</h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-3">Avg Duration</h6>
                                <h2 class="mb-0">{{ number_format(\App\Models\Movie::avg('duration')) }}</h2>
                                <p class="text-muted">minutes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Showtime Modal -->
<div class="modal fade" id="showtimeModal" tabindex="-1" aria-labelledby="showtimeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showtimeModalLabel">Connect Movie to Halls & Showtimes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.movieHallAssignments.store') }}" method="POST" id="showtimeForm">
                @csrf
                <input type="hidden" name="movie_id" id="modal-movie-id">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> You are connecting <strong id="movie-title-display"></strong> to a hall for specific showtimes.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="hall_id" class="form-label">Select Hall</label>
                                <select name="hall_id" id="hall_id" class="form-select" required>
                                    <option value="">-- Select Hall --</option>
                                    @foreach(\App\Models\Hall::where('is_active', true)->get() as $hall)
                                    <option value="{{ $hall->id }}">{{ $hall->name }} ({{ $hall->hall_type }}, {{ $hall->capacity }} seats)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">Showtimes</label>
                            
                            <!-- Existing Showtimes (will be populated by JavaScript) -->
                            <div id="existing-showtimes" class="mb-3 d-none">
                                <h6 class="text-muted mb-2">Existing Showtimes:</h6>
                                <div id="existing-showtimes-container">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>
                            
                            <h6 class="text-muted mb-2">Add New Showtimes:</h6>
                            <div class="showtime-inputs">
                                <div class="row mb-2 showtime-row">
                                    <div class="col-md-5">
                                        <input type="datetime-local" name="showtimes[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="is_active[]" value="1" checked>
                                            <label class="form-check-label">Active</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-showtime" disabled>
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="add-showtime">
                                    <i class="fas fa-plus me-1"></i> Add Another Showtime
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Showtimes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Showtime Modal
        const showtimeModal = document.getElementById('showtimeModal');
        if (showtimeModal) {
            // Function to fetch showtimes
            function fetchShowtimes(movieId) {
                const existingShowtimesContainer = document.getElementById('existing-showtimes');
                const existingShowtimesContent = document.getElementById('existing-showtimes-container');
                
                existingShowtimesContent.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading existing showtimes...</div>';
                
                try {
                    fetch(`{{ url('/api/movie-showtimes') }}/${movieId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('API Response:', data); // Debug log
                            
                            if (data.showtimes && Array.isArray(data.showtimes) && data.showtimes.length > 0) {
                                // Show the existing showtimes section
                                existingShowtimesContainer.classList.remove('d-none');
                                
                                let html = '';
                                data.showtimes.forEach(showtime => {
                                    try {
                                        // Safely get values with fallbacks
                                        const showtimeDate = showtime.showtime || new Date().toISOString();
                                        const isActive = showtime.is_active === true || showtime.is_active === 1;
                                        const hallName = showtime.hall_name || 'Unknown Hall';
                                        
                                        // Format date for display
                                        const date = new Date(showtimeDate);
                                        const formattedDate = date.toLocaleString('en-US', {
                                            weekday: 'short',
                                            year: 'numeric',
                                            month: 'short',
                                            day: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        });
                                        
                                        html += `
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-${isActive ? 'success' : 'secondary'} me-2">${formattedDate}</span>
                                                <span class="text-muted">Hall: ${hallName}</span>
                                            </div>
                                        </div>`;
                                    } catch (err) {
                                        console.error('Error formatting showtime:', err);
                                    }
                                });
                                
                                if (html) {
                                    existingShowtimesContent.innerHTML = html;
                                } else {
                                    existingShowtimesContent.innerHTML = '<div class="alert alert-warning">Error formatting showtimes data.</div>';
                                }
                            } else {
                                existingShowtimesContent.innerHTML = '<div class="alert alert-info">No existing showtimes found for this movie.</div>';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching showtimes:', error);
                            existingShowtimesContent.innerHTML = `
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Error loading existing showtimes. 
                                    <button class="btn btn-sm btn-outline-secondary ms-2" id="retry-fetch-btn">
                                        <i class="fas fa-sync-alt me-1"></i> Retry
                                    </button>
                                </div>`;
                            
                            // Add retry button functionality
                            const retryBtn = document.getElementById('retry-fetch-btn');
                            if (retryBtn) {
                                retryBtn.addEventListener('click', function() {
                                    // Re-trigger the fetch
                                    fetchShowtimes(movieId);
                                });
                            }
                        });
                } catch (err) {
                    console.error('Fatal error:', err);
                    existingShowtimesContent.innerHTML = '<div class="alert alert-danger">Unable to connect to the server. Please try again later.</div>';
                }
            }
            
            showtimeModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                const button = event.relatedTarget;
                
                // Extract movie info from data attributes
                const movieId = button.getAttribute('data-movie-id');
                const movieTitle = button.getAttribute('data-movie-title');
                
                // Update the modal's content
                const modalMovieId = document.getElementById('modal-movie-id');
                const movieTitleDisplay = document.getElementById('movie-title-display');
                const viewMovieShowtimesBtn = document.getElementById('view-movie-showtimes-btn');
                const existingShowtimesContainer = document.getElementById('existing-showtimes');
                const existingShowtimesContent = document.getElementById('existing-showtimes-container');
                
                // Set default values
                modalMovieId.value = movieId;
                movieTitleDisplay.textContent = movieTitle;
                
                // Reset form fields
                document.getElementById('showtimeForm').reset();
                
                // Reset showtimes to only one row
                const showtimeInputs = document.querySelector('.showtime-inputs');
                const firstShowtimeRow = document.querySelector('.showtime-row');
                
                // Remove all but the first showtime row
                while (showtimeInputs.children.length > 1) {
                    showtimeInputs.removeChild(showtimeInputs.lastChild);
                }
                
                // Clear the first showtime row
                const firstDateInput = firstShowtimeRow.querySelector('input[type="datetime-local"]');
                if (firstDateInput) {
                    firstDateInput.value = '';
                }
                
                // Set default datetime to next hour
                const now = new Date();
                now.setHours(now.getHours() + 1);
                now.setMinutes(0);
                
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                
                firstDateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
                
                // Update the view showtimes button URL
                if (viewMovieShowtimesBtn) {
                    viewMovieShowtimesBtn.href = `{{ url('/dashboard/movie-hall-assignments/movie') }}/${movieId}`;
                }
                
                // Fetch existing showtimes for this movie
                existingShowtimesContent.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading existing showtimes...</div>';
                existingShowtimesContainer.classList.remove('d-none');
                
                // Call the fetch function
                fetchShowtimes(movieId);
            });
        }
        
        // Add Showtime Button
        const addShowtimeBtn = document.getElementById('add-showtime');
        if (addShowtimeBtn) {
            addShowtimeBtn.addEventListener('click', function() {
                const showtimeInputs = document.querySelector('.showtime-inputs');
                const showtimeRow = document.querySelector('.showtime-row').cloneNode(true);
                
                // Clear the input value in the cloned row
                const dateInput = showtimeRow.querySelector('input[type="datetime-local"]');
                const checkbox = showtimeRow.querySelector('input[type="checkbox"]');
                dateInput.value = '';
                checkbox.checked = true;
                
                // Enable the remove button
                const removeBtn = showtimeRow.querySelector('.remove-showtime');
                removeBtn.disabled = false;
                removeBtn.addEventListener('click', function() {
                    showtimeRow.remove();
                });
                
                showtimeInputs.appendChild(showtimeRow);
            });
        }
        
        // Showtime Form Validation
        const showtimeForm = document.getElementById('showtimeForm');
        if (showtimeForm) {
            showtimeForm.addEventListener('submit', function(e) {
                // Get all datetime inputs
                const datetimeInputs = showtimeForm.querySelectorAll('input[type="datetime-local"]');
                const hallSelect = document.getElementById('hall_id');
                
                let hasError = false;
                
                // Clear previous error messages
                const errorMsgs = showtimeForm.querySelectorAll('.text-danger');
                errorMsgs.forEach(msg => msg.remove());
                
                // Validate hall selection
                if (!hallSelect.value) {
                    hasError = true;
                    const errorSpan = document.createElement('span');
                    errorSpan.className = 'text-danger d-block mt-1';
                    errorSpan.textContent = 'Please select a hall';
                    hallSelect.parentNode.appendChild(errorSpan);
                }
                
                // Check if at least one showtime is valid
                let hasValidShowtime = false;
                
                datetimeInputs.forEach(input => {
                    if (input.value) {
                        const selectedDateTime = new Date(input.value);
                        const now = new Date();
                        
                        if (selectedDateTime <= now) {
                            const errorSpan = document.createElement('span');
                            errorSpan.className = 'text-danger d-block mt-1';
                            errorSpan.textContent = 'Showtime must be in the future';
                            input.parentNode.appendChild(errorSpan);
                            hasError = true;
                        } else {
                            hasValidShowtime = true;
                        }
                    }
                });
                
                if (!hasValidShowtime) {
                    hasError = true;
                    // Add an error message to the first showtime row
                    const firstRow = showtimeForm.querySelector('.showtime-row');
                    const errorSpan = document.createElement('span');
                    errorSpan.className = 'text-danger d-block mt-1';
                    errorSpan.textContent = 'At least one showtime is required';
                    firstRow.querySelector('.col-md-5').appendChild(errorSpan);
                }
                
                if (hasError) {
                    e.preventDefault();
                    return false;
                }
                
                // Disable submit button to prevent multiple submissions
                const submitBtn = showtimeForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
                
                return true;
            });
        }
        
        // Genre Chart
        const genreCtx = document.getElementById('genreChart').getContext('2d');
        new Chart(genreCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach(\App\Models\Movie::select('genre')->distinct()->get() as $genreRow)
                        '{{ $genreRow->genre }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach(\App\Models\Movie::select('genre')->distinct()->get() as $genreRow)
                            {{ \App\Models\Movie::where('genre', $genreRow->genre)->count() }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#4361ee',
                        '#3a0ca3',
                        '#7209b7',
                        '#f72585',
                        '#4cc9f0',
                        '#4895ef',
                        '#560bad',
                        '#b5179e'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1.2,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12
                        }
                    }
                }
            }
        });
        
        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Now Showing', 'Coming Soon', 'Not Showing'],
                datasets: [{
                    data: [
                        {{ \App\Models\Movie::where('is_showing', true)->count() }},
                        {{ \App\Models\Movie::where('is_showing', false)->where('release_date', '>', now())->count() }},
                        {{ \App\Models\Movie::where('is_showing', false)->where('release_date', '<=', now())->count() }}
                    ],
                    backgroundColor: [
                        '#4cc9f0',
                        '#4361ee',
                        '#6c757d'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1.2,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 10
                        }
                    }
                }
            }
        });
        
        // AJAX Pagination
        document.addEventListener('click', function(e) {
            const target = e.target;
            
            // Check if clicked element is a pagination link
            if (target.tagName === 'A' && target.closest('#pagination-links') !== null) {
                e.preventDefault();
                
                const url = target.getAttribute('href');
                if (!url) return;
                
                // Update URL without reloading the page
                window.history.pushState({}, '', url);
                
                // Create loading overlay
                const loadingOverlay = document.createElement('div');
                loadingOverlay.className = 'loading-overlay';
                loadingOverlay.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                
                // Get the table container and add the loading overlay
                const tableContainer = document.querySelector('.table-responsive').closest('.card-body');
                tableContainer.appendChild(loadingOverlay);
                
                // Fetch the new content
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(html => {
                        // Create a temporary element to parse the HTML
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;
                        
                        // Get the table body content from the fetched HTML
                        const newTableBody = tempDiv.querySelector('#movies-table-body');
                        const newPaginationLinks = tempDiv.querySelector('#pagination-links');
                        const newPaginationInfo = tempDiv.querySelector('.pagination-info');
                        
                        // Update the current page with the new content
                        if (newTableBody) {
                            document.getElementById('movies-table-body').innerHTML = newTableBody.innerHTML;
                        }
                        
                        if (newPaginationLinks) {
                            document.getElementById('pagination-links').innerHTML = newPaginationLinks.innerHTML;
                        }
                        
                        if (newPaginationInfo) {
                            document.querySelector('.pagination-info').innerHTML = newPaginationInfo.innerHTML;
                        }
                        
                        // Remove the loading overlay
                        tableContainer.removeChild(loadingOverlay);
                        
                        // Reinitialize tooltips
                        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl)
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching content:', error);
                        // Show error message in the table body
                        document.getElementById('movies-table-body').innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h5>Error loading data</h5>
                                        <p class="text-muted">Please try again or refresh the page</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                        // Remove the loading overlay
                        tableContainer.removeChild(loadingOverlay);
                    });
            }
        });
    });
</script>
@endsection 