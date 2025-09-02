@extends('layouts.app')

@section('title', 'Halls Dashboard - Cinema Management')

@section('page-title', 'Halls Dashboard')

@section('actions')
<a href="{{ route('halls.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i> Add New Hall
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
</style>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex flex-column flex-md-row justify-content-end align-items-start align-items-md-center gap-3">
                    <h5 class="mb-0 fw-bold text-primary me-auto">
                        <i class="fas fa-theater-masks me-2"></i>Halls List
                    </h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100 w-sm-auto" type="button" id="hallTypeFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(request()->has('hall_type'))
                                    {{ request('hall_type') }}
                                @else
                                    All Types
                                @endif
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="hallTypeFilter">
                                <li><a class="dropdown-item" href="{{ route('dashboard.halls') }}">All Types</a></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.halls', ['hall_type' => 'Standard']) }}">Standard</a></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.halls', ['hall_type' => 'VIP']) }}">VIP</a></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.halls', ['hall_type' => 'IMAX']) }}">IMAX</a></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.halls', ['hall_type' => '3D']) }}">3D</a></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.halls', ['hall_type' => '4DX']) }}">4DX</a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100 w-sm-auto" type="button" id="statusFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(request()->has('is_active'))
                                    {{ request('is_active') ? 'Active' : 'Inactive' }}
                                @else
                                    All Status
                                @endif
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="statusFilter">
                                <li><a class="dropdown-item" href="{{ route('dashboard.halls') }}">All Status</a></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.halls', ['is_active' => 1]) }}">Active</a></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.halls', ['is_active' => 0]) }}">Inactive</a></li>
                            </ul>
                        </div>
                        <a href="{{ route('halls.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> New Hall
                        </a>
                    </div>
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
                                <th>Name</th>
                                <th class="d-none d-md-table-cell">Type</th>
                                <th class="d-none d-lg-table-cell">Capacity</th>
                                <th>Seats Count</th>
                                <th class="d-none d-md-table-cell">Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="halls-table-body">
                            @forelse($halls as $hall)
                            <tr>
                                <td class="fw-medium" data-label="Name">
                                    <div>{{ $hall->name }}</div>
                                    <small class="text-muted d-md-none">
                                        {{ $hall->hall_type }} • {{ $hall->capacity }} seats •
                                        @if($hall->is_active)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Inactive</span>
                                        @endif
                                    </small>
                                </td>
                                <td class="d-none d-md-table-cell" data-label="Type">
                                    <span class="badge bg-primary">{{ $hall->hall_type }}</span>
                                </td>
                                <td class="d-none d-lg-table-cell" data-label="Capacity">{{ $hall->capacity }} seats</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">{{ $hall->seats->count() }}</span>
                                        <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                                            @php
                                                $percentage = $hall->capacity > 0 ? min(100, ($hall->seats->count() / $hall->capacity) * 100) : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell" data-label="Status">
                                    @if($hall->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td data-label="Actions">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('halls.show', $hall->id) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('halls.edit', $hall->id) }}" class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('seats.create', ['hall_id' => $hall->id]) }}" class="btn btn-outline-success" data-bs-toggle="tooltip" title="Add Seats">
                                            <i class="fas fa-chair"></i>
                                        </a>
                                        <button type="submit" form="delete-form-{{ $hall->id }}" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this hall?')" data-bs-toggle="tooltip" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $hall->id }}" action="{{ route('halls.destroy', $hall->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-theater-masks fa-3x text-muted mb-3"></i>
                                        <h5>No halls found</h5>
                                        <p class="text-muted">Get started by adding your first cinema hall</p>
                                        <a href="{{ route('halls.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus me-1"></i> Add New Hall
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
                        Showing {{ $halls->firstItem() ?? 0 }} to {{ $halls->lastItem() ?? 0 }} of {{ $halls->total() }} results
                    </div>
                    <div id="pagination-links">
                        {{ $halls->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Hall Distribution by Type</h5>
            </div>
            <div class="card-body" style="height: 300px;">
                <canvas id="hallTypeChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Seating Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $totalCapacity = $halls->sum('capacity');
                        $totalConfigured = $halls->sum(function($hall) {
                            return $hall->seats->count();
                        });
                        $configurationPercentage = $totalCapacity > 0 ? ($totalConfigured / $totalCapacity) * 100 : 0;
                    @endphp
                    
                    <div class="col-6 mb-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-3">Total Capacity</h6>
                                <h2 class="mb-0">{{ $totalCapacity }}</h2>
                                <p class="text-muted">seats</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6 mb-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-3">Configured Seats</h6>
                                <h2 class="mb-0">{{ $totalConfigured }}</h2>
                                <p class="text-muted">seats ({{ number_format($configurationPercentage, 1) }}%)</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-3">Active Halls</h6>
                                <h2 class="mb-0">{{ $halls->where('is_active', true)->count() }}</h2>
                                <p class="text-muted">halls</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-3">Inactive Halls</h6>
                                <h2 class="mb-0">{{ $halls->where('is_active', false)->count() }}</h2>
                                <p class="text-muted">halls</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Hall Type Chart
        const ctx = document.getElementById('hallTypeChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($halls->groupBy('hall_type') as $type => $groupedHalls)
                        '{{ $type }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($halls->groupBy('hall_type') as $type => $groupedHalls)
                            {{ $groupedHalls->count() }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#4361ee',
                        '#3a0ca3',
                        '#7209b7',
                        '#f72585',
                        '#4cc9f0'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1.5,
                plugins: {
                    legend: {
                        position: 'bottom',
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
                        const newTableBody = tempDiv.querySelector('#halls-table-body');
                        const newPaginationLinks = tempDiv.querySelector('#pagination-links');
                        const newPaginationInfo = tempDiv.querySelector('.pagination-info');
                        
                        // Update the current page with the new content
                        if (newTableBody) {
                            document.getElementById('halls-table-body').innerHTML = newTableBody.innerHTML;
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
                        document.getElementById('halls-table-body').innerHTML = `
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