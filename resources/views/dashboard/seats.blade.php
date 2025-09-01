@extends('layouts.app')

@section('title', 'Seats Dashboard - Cinema Management')

@section('styles')
<style>
    .hover-shadow:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        transition: box-shadow 0.3s ease-in-out;
    }
    .card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }
    .table-responsive {
        border-radius: 0.25rem;
    }
    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
    }
    .seat-badge {
        min-width: 80px;
        display: inline-block;
        text-align: center;
    }
    /* Table styling */
    .table {
        border-collapse: separate;
        border-spacing: 0;
    }
    .table th {
        font-weight: 600;
        border-bottom-width: 2px;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,0.04);
    }
    /* Pagination styling */
    .pagination-sm .page-link {
        padding: 0.2rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1;
    }
    .pagination-text {
        font-size: 0.75rem;
        color: #6c757d;
    }
    /* Button styling */
    .btn-group .btn {
        border-radius: 0.2rem;
        margin-right: 0.125rem;
    }
    .icon-box {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    /* Loading indicator */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .spinner-border.spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.15em;
    }
    /* Active page link styling */
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .seat-box { margin: 0 2px; }
    .seat-regular { background: #fff; color: #333; border: 1px solid #bbb; }
    .seat-premium { background: #ffc107; color: #fff; }
    .seat-vip { background: #dc3545; color: #fff; }
    .seat-unavailable { background: #adb5bd; color: #fff; opacity: 0.6; text-decoration: line-through; }
    .seat-empty { background: transparent; border: none; }
</style>
@endsection

@section('page-title', 'Seats Dashboard')

@section('actions')
<a href="{{ route('seats.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i> Add New Seat
</a>
@endsection

@section('content')
<div class="row mb-4">
    @foreach($halls as $hall)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm hover-shadow">
            <div class="card-header bg-primary bg-opacity-10 py-3 border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">{{ $hall->name }}</h5>
                    <span class="badge {{ $hall->is_active ? 'bg-success' : 'bg-danger' }} rounded-pill">
                        {{ $hall->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-tag text-primary me-3 fa-fw"></i>
                    <div>
                        <p class="mb-0">{{ $hall->hall_type }}</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-users text-success me-3 fa-fw"></i>
                    <div>
                        <p class="mb-0">Capacity: {{ $hall->capacity }}</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-chair text-info me-3 fa-fw"></i>
                    <div>
                        <p class="mb-0">Seats: {{ $hall->seats->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 bg-light">
                <a href="{{ route('halls.show', $hall->id) }}" class="btn btn-primary w-100">
                    <i class="fas fa-eye me-2"></i> View Hall Details
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                <h5 class="mb-0 text-primary">
                    <i class="fas fa-chair me-2"></i> Seats List
                </h5>
                <div>
                    <select class="form-select form-select-sm d-inline-block w-auto border-primary" id="hallFilter">
                        <option value="">All Halls</option>
                        @foreach($halls as $hall)
                        <option value="{{ $hall->id }}">{{ $hall->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive position-relative" id="seats-table-container">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Hall</th>
                                <th>Row</th>
                                <th>Number</th>
                                <th>Type</th>
                                <th>Additional Charge</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="seats-table-body">
                            @forelse($seats as $seat)
                            <tr data-hall="{{ $seat->hall_id }}">
                                <td><span class="fw-medium">{{ $seat->hall->name }}</span></td>
                                <td>{{ $seat->row }}</td>
                                <td>{{ $seat->number }}</td>
                                <td>
                                    <span class="badge bg-info text-white seat-badge rounded-pill">{{ ucfirst($seat->type) }}</span>
                                </td>
                                <td><span class="text-success">${{ number_format($seat->additional_charge, 2) }}</span></td>
                                <td>
                                    @if($seat->is_available)
                                        <span class="badge bg-success seat-badge rounded-pill">Available</span>
                                    @else
                                        <span class="badge bg-danger seat-badge rounded-pill">Unavailable</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('seats.show', $seat->id) }}" class="btn btn-sm btn-info text-white" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('seats.edit', $seat->id) }}" class="btn btn-sm btn-warning text-white" title="Edit Seat">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('seats.destroy', $seat->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this seat?')" title="Delete Seat">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-chair fa-2x mb-3"></i>
                                    <p class="mb-0">No seats found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="text-muted pagination-text">
                            Showing {{ $seats->firstItem() ?? 0 }} to {{ $seats->lastItem() ?? 0 }} of {{ $seats->total() }} results
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item {{ $seats->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $seats->previousPageUrl() }}">&laquo;</a>
                                </li>
                                @for ($i = 1; $i <= min(5, $seats->lastPage()); $i++)
                                    <li class="page-item {{ $seats->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $seats->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item {{ !$seats->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $seats->nextPageUrl() }}">&raquo;</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hallFilter = document.getElementById('hallFilter');
        const tableBody = document.getElementById('seats-table-body');
        const tableContainer = document.getElementById('seats-table-container');
        const paginationContainer = document.querySelector('.pagination');
        const resultInfo = document.querySelector('.pagination-text');
        
        // Hall filter functionality
        hallFilter.addEventListener('change', function() {
            const hallId = this.value;
            const rows = document.querySelectorAll('#seats-table-body tr[data-hall]');
            
            rows.forEach(row => {
                if (!hallId || row.getAttribute('data-hall') === hallId) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // AJAX pagination
        document.addEventListener('click', function(e) {
            // If user clicked on a pagination link or its child elements
            const pageLink = e.target.closest('.page-link');
            if (!pageLink) return;
            
            const pageItem = pageLink.closest('.page-item');
            if (!pageItem || pageItem.classList.contains('disabled') || pageItem.classList.contains('active')) {
                return; // Don't process if disabled or current page
            }
            
            e.preventDefault();
            const url = pageLink.getAttribute('href');
            if (!url) return;
            
            // Create and show loading overlay
            const loader = document.createElement('div');
            loader.className = 'loading-overlay';
            loader.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
            tableContainer.appendChild(loader);
            
            // Disable all page links during loading
            document.querySelectorAll('.page-link').forEach(link => {
                link.style.pointerEvents = 'none';
                link.style.opacity = '0.5';
            });
            
            // Fetch the page content
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extract the table body content
                    const newTableBody = doc.getElementById('seats-table-body');
                    if (newTableBody) {
                        tableBody.innerHTML = newTableBody.innerHTML;
                    }
                    
                    // Update pagination links
                    const newPagination = doc.querySelector('.pagination');
                    if (newPagination) {
                        paginationContainer.innerHTML = newPagination.innerHTML;
                    }
                    
                    // Update result count
                    const newResultInfo = doc.querySelector('.pagination-text');
                    if (newResultInfo) {
                        resultInfo.innerHTML = newResultInfo.innerHTML;
                    }
                    
                    // Update URL without refreshing
                    window.history.pushState({}, '', url);
                })
                .catch(error => {
                    console.error('Error loading page:', error);
                    tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-danger"><i class="fas fa-exclamation-circle me-2"></i>Error loading data. Please try again.</td></tr>';
                })
                .finally(() => {
                    // Remove loading overlay
                    const existingLoader = tableContainer.querySelector('.loading-overlay');
                    if (existingLoader) {
                        existingLoader.remove();
                    }
                    
                    // Re-enable pagination links
                    document.querySelectorAll('.page-link').forEach(link => {
                        link.style.pointerEvents = '';
                        link.style.opacity = '';
                    });
                });
        });
    });
</script>
@endsection 