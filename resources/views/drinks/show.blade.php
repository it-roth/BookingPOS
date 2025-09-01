@extends('layouts.app')

@section('title', 'Drink Details - Cinema Management')

@section('page-title', 'Drink Details')

@section('actions')
<div class="btn-group">
    <a href="{{ route('dashboard.drinks') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Drinks
    </a>
    <a href="{{ route('drinks.edit', $drink->id) }}" class="btn btn-warning text-white">
        <i class="fas fa-edit me-1"></i> Edit
    </a>
    <form action="{{ route('drinks.destroy', $drink->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this drink?')">
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
                @if($drink->image)
                    <img src="{{ asset($drink->image) }}" alt="{{ $drink->name }}" class="img-fluid rounded mb-3" style="max-height: 200px;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
                        <i class="fas fa-glass-cheers fa-4x text-secondary"></i>
                    </div>
                @endif
                
                <h4 class="card-title">{{ $drink->name }}</h4>
                <p class="badge bg-info">{{ $drink->category }}</p>
                <p class="badge bg-secondary">{{ ucfirst($drink->size) }}</p>
                
                <div class="mt-3">
                    <span class="badge bg-{{ $drink->is_available ? 'success' : 'danger' }}">
                        {{ $drink->is_available ? 'Available' : 'Not Available' }}
                    </span>
                </div>
                
                <h3 class="mt-3 text-success">${{ number_format($drink->price, 2) }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Drink Information</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">Description</h5>
                <p class="card-text">{{ $drink->description ?: 'No description available.' }}</p>
                
                <hr>
                
                <h5 class="card-title">Details</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="150">Name</th>
                            <td>{{ $drink->name }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $drink->category }}</td>
                        </tr>
                        <tr>
                            <th>Size</th>
                            <td>{{ ucfirst($drink->size) }}</td>
                        </tr>
                        <tr>
                            <th>Price</th>
                            <td>${{ number_format($drink->price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Availability</th>
                            <td>
                                <span class="badge bg-{{ $drink->is_available ? 'success' : 'danger' }}">
                                    {{ $drink->is_available ? 'Available' : 'Not Available' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <span class="text-muted">
                        <small>Created: {{ $drink->created_at->format('M d, Y H:i') }}</small>
                    </span>
                    <span class="text-muted">
                        <small>Last Updated: {{ $drink->updated_at->format('M d, Y H:i') }}</small>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 