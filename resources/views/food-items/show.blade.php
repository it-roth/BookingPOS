@extends('layouts.app')

@section('title', $foodItem->name . ' - Cinema Management')

@section('page-title', 'Food Item Details')

@section('actions')
<div class="btn-group">
    <a href="{{ route('dashboard.food-items') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Food Items
    </a>
    <a href="{{ route('food-items.edit', $foodItem->id) }}" class="btn btn-warning text-white">
        <i class="fas fa-edit me-1"></i> Edit
    </a>
    <form action="{{ route('food-items.destroy', $foodItem->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this food item?')">
            <i class="fas fa-trash me-1"></i> Delete
        </button>
    </form>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                @if($foodItem->image)
                    <img src="{{ asset($foodItem->image) }}" alt="{{ $foodItem->name }}" class="img-fluid rounded mb-3" style="max-height: 200px;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
                        <i class="fas fa-utensils fa-4x text-secondary"></i>
                    </div>
                @endif
                
                <h4 class="card-title">{{ $foodItem->name }}</h4>
                <p class="badge bg-info">{{ $foodItem->category }}</p>
                
                <div class="mt-3">
                    <span class="badge bg-{{ $foodItem->is_available ? 'success' : 'danger' }}">
                        {{ $foodItem->is_available ? 'Available' : 'Not Available' }}
                    </span>
                </div>
                
                <h3 class="mt-3 text-success">${{ number_format($foodItem->price, 2) }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Food Item Information</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">Description</h5>
                <p class="card-text">{{ $foodItem->description ?: 'No description available.' }}</p>
                
                <hr>
                
                <h5 class="card-title">Details</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="150">Name</th>
                            <td>{{ $foodItem->name }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $foodItem->category }}</td>
                        </tr>
                        <tr>
                            <th>Price</th>
                            <td>${{ number_format($foodItem->price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Preparation Time</th>
                            <td>{{ $foodItem->preparation_time ? $foodItem->preparation_time . ' minutes' : 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th>Availability</th>
                            <td>
                                <span class="badge bg-{{ $foodItem->is_available ? 'success' : 'danger' }}">
                                    {{ $foodItem->is_available ? 'Available' : 'Not Available' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <span class="text-muted">
                        <small>Created: {{ $foodItem->created_at->format('M d, Y H:i') }}</small>
                    </span>
                    <span class="text-muted">
                        <small>Last Updated: {{ $foodItem->updated_at->format('M d, Y H:i') }}</small>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 