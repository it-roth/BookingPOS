@extends('layouts.app')

@section('title', 'Food Items - Cinema Management')

@section('page-title', 'Food Items')

@section('actions')
<a href="{{ route('food-items.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i> Add Food Item
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Food Items</h5>
            <form action="{{ route('dashboard.food-items') }}" method="GET" class="d-flex gap-2">
                <select name="category_filter" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">All Categories</option>
                    <option value="Popcorn" {{ request('category_filter') == 'Popcorn' ? 'selected' : '' }}>Popcorn</option>
                    <option value="Snacks" {{ request('category_filter') == 'Snacks' ? 'selected' : '' }}>Snacks</option>
                    <option value="Hot Food" {{ request('category_filter') == 'Hot Food' ? 'selected' : '' }}>Hot Food</option>
                    <option value="Dessert" {{ request('category_filter') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                    <option value="Combo" {{ request('category_filter') == 'Combo' ? 'selected' : '' }}>Combo</option>
                </select>
                <select name="availability_filter" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">All Availability</option>
                    <option value="1" {{ request('availability_filter') == '1' ? 'selected' : '' }}>Available</option>
                    <option value="0" {{ request('availability_filter') == '0' ? 'selected' : '' }}>Not Available</option>
                </select>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search food items..." value="{{ request('search') }}" style="width: 200px;">
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('dashboard.food-items') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-redo"></i>
                </a>
            </form>
        </div>
    </div>
    <div class="card-body">
        @if($foodItems->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="150" class="text-center">Food Item</th>
                            <th>Preparation</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th width="120">Availability</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($foodItems as $item)
                            <tr class="align-middle">
                                <td>
                                    <div class="d-flex flex-column align-items-center">
                                        <div style="width: 80px; height: 100px; overflow: hidden; margin-bottom: 10px;">
                                            @if($item->image)
                                                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                    <i class="fas fa-hamburger fa-3x text-secondary"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <strong>{{ $item->name }}</strong>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="small text-muted">
                                        {{ $item->preparation_time ? $item->preparation_time . ' mins prep time' : 'No prep time' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $item->category }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="fw-bold">${{ number_format($item->price, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $item->is_available ? 'success' : 'danger' }}">
                                        {{ $item->is_available ? 'Available' : 'Not Available' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('food-items.show', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('food-items.edit', $item->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('food-items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this food item?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $foodItems->links() }}
            </div>
        @else
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i>
                No food items found.
                @if(request('search') || request('category_filter') || request('availability_filter'))
                    <a href="{{ route('dashboard.food-items') }}" class="alert-link ms-2">Clear all filters</a>
                @else
                    <a href="{{ route('food-items.create') }}" class="alert-link ms-2">Add your first food item</a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection 