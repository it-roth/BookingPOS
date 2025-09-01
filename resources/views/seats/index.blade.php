@extends('layouts.app')

@section('title', 'Seats Management - Cinema Management')

@section('page-title', 'Seats Management')

@section('actions')
<a href="{{ route('seats.create') }}" class="btn btn-primary">
    <i class="fas fa-plus-circle me-1"></i> Add New Seat
</a>
<a href="{{ route('seats.bulkCreate') }}" class="btn btn-success ms-2">
    <i class="fas fa-th me-1"></i> Bulk Add Seats
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Seats</h5>
        <div class="d-flex">
            <form action="{{ route('seats.index') }}" method="GET" class="d-flex">
                <select name="hall_filter" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                    <option value="">All Halls</option>
                    @foreach(\App\Models\Hall::all() as $hall)
                        <option value="{{ $hall->id }}" {{ request('hall_filter') == $hall->id ? 'selected' : '' }}>
                            {{ $hall->name }}
                        </option>
                    @endforeach
                </select>
                
                <select name="type_filter" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <option value="regular" {{ request('type_filter') == 'regular' ? 'selected' : '' }}>Regular</option>
                    <option value="premium" {{ request('type_filter') == 'premium' ? 'selected' : '' }}>Premium</option>
                    <option value="vip" {{ request('type_filter') == 'vip' ? 'selected' : '' }}>VIP</option>
                </select>
            </form>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
            </div>
        @endif
        
        @if($seats->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hall</th>
                            <th>Row</th>
                            <th>Number</th>
                            <th>Type</th>
                            <th>Additional Charge</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($seats as $seat)
                            <tr>
                                <td>{{ $seat->id }}</td>
                                <td>{{ $seat->hall->name ?? 'N/A' }}</td>
                                <td>{{ $seat->row }}</td>
                                <td>{{ $seat->number }}</td>
                                <td>
                                    @if($seat->type == 'regular')
                                        <span class="badge bg-info">Regular</span>
                                    @elseif($seat->type == 'premium')
                                        <span class="badge bg-warning">Premium</span>
                                    @elseif($seat->type == 'vip')
                                        <span class="badge bg-danger">VIP</span>
                                    @endif
                                </td>
                                <td>${{ number_format($seat->additional_charge, 2) }}</td>
                                <td>
                                    @if($seat->is_available)
                                        <span class="badge bg-success">Available</span>
                                    @else
                                        <span class="badge bg-secondary">Unavailable</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('seats.edit', $seat) }}" class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('seats.show', $seat) }}" class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('seats.destroy', $seat) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this seat?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
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
            
            <div class="mt-4">
                {{ $seats->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No seats found. Add some seats to get started.
            </div>
        @endif
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Seat Distribution Summary</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">By Type</div>
                    <div class="card-body">
                        @php
                            $regularCount = \App\Models\Seat::where('type', 'regular')->count();
                            $premiumCount = \App\Models\Seat::where('type', 'premium')->count();
                            $vipCount = \App\Models\Seat::where('type', 'vip')->count();
                            $totalSeats = $regularCount + $premiumCount + $vipCount;
                            
                            $regularPercent = $totalSeats > 0 ? round(($regularCount / $totalSeats) * 100) : 0;
                            $premiumPercent = $totalSeats > 0 ? round(($premiumCount / $totalSeats) * 100) : 0;
                            $vipPercent = $totalSeats > 0 ? round(($vipCount / $totalSeats) * 100) : 0;
                        @endphp
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Regular Seats</span>
                                <span>{{ $regularCount }} ({{ $regularPercent }}%)</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-info" style="width: {{ $regularPercent }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Premium Seats</span>
                                <span>{{ $premiumCount }} ({{ $premiumPercent }}%)</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: {{ $premiumPercent }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>VIP Seats</span>
                                <span>{{ $vipCount }} ({{ $vipPercent }}%)</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-danger" style="width: {{ $vipPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">By Hall</div>
                    <div class="card-body">
                        @php
                            $halls = \App\Models\Hall::withCount('seats')->get();
                        @endphp
                        
                        @if($halls->count() > 0)
                            @foreach($halls as $hall)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>{{ $hall->name }}</span>
                                        <span>{{ $hall->seats_count }} seats</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" style="width: {{ $hall->capacity > 0 ? min(100, round(($hall->seats_count / $hall->capacity) * 100)) : 0 }}%"></div>
                                    </div>
                                    <div class="small text-muted text-end">
                                        {{ $hall->seats_count }}/{{ $hall->capacity }} ({{ $hall->capacity > 0 ? round(($hall->seats_count / $hall->capacity) * 100) : 0 }}% of capacity)
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                No halls found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 