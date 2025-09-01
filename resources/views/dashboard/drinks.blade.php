@extends('layouts.app')

@section('title', 'Drinks  - Cinema Management')

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
        border-radius: 0.25rem !important;
        margin-right: 2px;
        border-width: 1px;
        box-shadow: none !important;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-1px);
        transition: transform 0.2s ease;
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
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endsection

@section('page-title', 'Drinks')

@section('actions')
<a href="{{ route('drinks.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i> Add New Drink
</a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-primary">
            <i class="fas fa-glass-martini-alt me-2"></i> Drinks List
        </h5>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ request('category') ? request('category') : 'All Categories' }} <i class="fas fa-chevron-down ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="categoryDropdown">
                    <li><a class="dropdown-item" href="{{ route('dashboard.drinks') }}">All Categories</a></li>
                    @foreach($drinks->pluck('category')->unique() as $category)
                        <li><a class="dropdown-item" href="{{ route('dashboard.drinks', ['category' => $category]) }}">{{ $category }}</a></li>
                    @endforeach
                </ul>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="sizeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ request('size') ? ucfirst(request('size')) : 'All Sizes' }} <i class="fas fa-chevron-down ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sizeDropdown">
                    <li><a class="dropdown-item" href="{{ route('dashboard.drinks') }}">All Sizes</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.drinks', ['size' => 'small']) }}">Small</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.drinks', ['size' => 'regular']) }}">Regular</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.drinks', ['size' => 'large']) }}">Large</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="table-responsive position-relative" id="drinks-table-container">
        @if($drinks->count() > 0)
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 ps-4 text-center">Name</th>
                        <th class="border-0 text-center">Category</th>
                        <th class="border-0 text-center">Size</th>
                        <th class="border-0 text-center">Price</th>
                        <th class="border-0 text-center">Status</th>
                        <th class="border-0 text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody id="drinks-table-body">
                    @foreach($drinks as $drink)
                        <tr class="align-middle">
                            <td class="ps-4">
                                <div class="d-flex flex-column align-items-center">
                                    <div style="width: 80px; height: 100px; overflow: hidden; margin-bottom: 10px;">
                                        @if($drink->image)
                                            <img src="{{ asset($drink->image) }}" alt="{{ $drink->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                <i class="fas fa-glass-martini-alt fa-3x text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <strong>{{ $drink->name }}</strong>
                                </div>
                            </td>
                            <td class="text-center">{{ $drink->category }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary rounded-pill">{{ ucfirst($drink->size) }}</span>
                            </td>
                            <td class="text-center"><span class="text-success">${{ number_format($drink->price, 2) }}</span></td>
                            <td class="text-center">
                                @if($drink->is_available)
                                    <span class="badge bg-success rounded-pill">Available</span>
                                @else
                                    <span class="badge bg-danger rounded-pill">Not Available</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('drinks.show', $drink->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('drinks.edit', $drink->id) }}" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit Drink">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="submit" form="delete-drink-{{ $drink->id }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this drink?')" data-bs-toggle="tooltip" title="Delete Drink">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-drink-{{ $drink->id }}" action="{{ route('drinks.destroy', $drink->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="mt-3 px-4 pb-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="text-muted pagination-text">
                        Showing {{ $drinks->firstItem() ?? 0 }} to {{ $drinks->lastItem() ?? 0 }} of {{ $drinks->total() }} results
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item {{ $drinks->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $drinks->previousPageUrl() }}">&laquo;</a>
                            </li>
                            @for ($i = 1; $i <= min(5, $drinks->lastPage()); $i++)
                                <li class="page-item {{ $drinks->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $drinks->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ !$drinks->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $drinks->nextPageUrl() }}">&raquo;</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        @else
            <div class="alert alert-info m-4">
                <i class="fas fa-info-circle me-2"></i> No drinks found.
                <a href="{{ route('drinks.create') }}" class="alert-link">add a new drink</a>.
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        const tableBody = document.getElementById('drinks-table-body');
        const tableContainer = document.getElementById('drinks-table-container');
        const paginationContainer = document.querySelector('.pagination');
        const resultInfo = document.querySelector('.pagination-text');
        
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
                    const newTableBody = doc.getElementById('drinks-table-body');
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
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-danger"><i class="fas fa-exclamation-circle me-2"></i>Error loading data. Please try again.</td></tr>';
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