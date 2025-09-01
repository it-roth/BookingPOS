@extends('layouts.app')

@section('title', 'Drinks - Cinema Management')

@section('page-title', 'Drinks List')

@section('actions')
<a href="{{ route('drinks.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i> Add New Drink
</a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Drinks List</h5>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    All Categories <i class="fas fa-chevron-down ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="categoryDropdown">
                    <li><a class="dropdown-item" href="{{ route('dashboard.drinks') }}">All Categories</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.drinks', ['category' => 'Soft Drinks']) }}">Soft Drinks</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.drinks', ['category' => 'Juices']) }}">Juices</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.drinks', ['category' => 'Hot Beverages']) }}">Hot Beverages</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.drinks', ['category' => 'Water']) }}">Water</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.drinks', ['category' => 'Alcoholic']) }}">Alcoholic Beverages</a></li>
                </ul>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sizeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    All Sizes <i class="fas fa-chevron-down ms-1"></i>
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
    
    <div class="table-responsive">
        @if($drinks->count() > 0)
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 ps-4">Name</th>
                        <th class="border-0">Category</th>
                        <th class="border-0">Size</th>
                        <th class="border-0">Price</th>
                        <th class="border-0">Status</th>
                        <th class="border-0 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drinks as $drink)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div style="width: 40px; height: 40px; overflow: hidden; border-radius: 4px; margin-right: 12px;">
                                        @if($drink->image)
                                            <img src="{{ asset($drink->image) }}" alt="{{ $drink->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                <i class="fas fa-glass-martini-alt text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <strong>{{ $drink->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $drink->category }}</td>
                            <td>
                                <span class="badge bg-secondary text-white">{{ ucfirst($drink->size) }}</span>
                            </td>
                            <td>${{ number_format($drink->price, 2) }}</td>
                            <td>
                                @if($drink->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Not Available</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('drinks.show', $drink->id) }}" class="btn btn-sm btn-info text-white rounded-circle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('drinks.edit', $drink->id) }}" class="btn btn-sm btn-warning text-white rounded-circle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger rounded-circle" 
                                        onclick="event.preventDefault(); 
                                                if(confirm('Are you sure you want to delete this drink?')) {
                                                    document.getElementById('delete-drink-{{ $drink->id }}').submit();
                                                }">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-drink-{{ $drink->id }}" action="{{ route('drinks.destroy', $drink->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center py-3">
                {{ $drinks->links() }}
            </div>
        @else
            <div class="alert alert-info m-4">
                <i class="fas fa-info-circle me-2"></i> No drinks found.
                @if(request('category') || request('size') || request('availability'))
                    <a href="{{ route('dashboard.drinks') }}" class="alert-link ms-2">Clear filters</a> or 
                @endif
                <a href="{{ route('drinks.create') }}" class="alert-link">add a new drink</a>.
            </div>
        @endif
    </div>
</div>
@endsection 