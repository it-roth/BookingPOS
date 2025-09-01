@extends('layouts.app')

@section('title', 'Booking Reports - Cinema Management')

@section('page-title', 'Booking & Revenue Reports')

@section('styles')
<style>
    .summary-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
        transition: transform 0.2s;
    }
    
    .summary-card:hover {
        transform: translateY(-5px);
    }
    
    .summary-card .card-body {
        padding: 1.5rem;
    }
    
    .summary-card .card-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.75rem;
    }
    
    .summary-card .metric {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .summary-card .icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .top-movies-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .top-movies-card .movie-row {
        border-left: 3px solid transparent;
        transition: background-color 0.2s;
    }
    
    .top-movies-card .movie-row:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .top-movies-card .movie-row.top-1 {
        border-left-color: #ffc107;
    }
    
    .top-movies-card .movie-row.top-2 {
        border-left-color: #6c757d;
    }
    
    .top-movies-card .movie-row.top-3 {
        border-left-color: #cd7f32;
    }
    
    .booking-table td, .booking-table th {
        vertical-align: middle;
    }
    
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        font-weight: 600;
    }
    
    .booking-details-row {
        background-color: #f8f9fa;
        font-size: 0.875rem;
    }
    
    .export-dropdown .dropdown-item i {
        width: 20px;
    }

    /* Print styles */
    @media print {
        .no-print,
        .card-header,
        .btn,
        .dropdown,
        .pagination,
        .sidebar,
        .main-header {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .table {
            font-size: 12px;
        }

        .summary-card {
            border: 1px solid #000 !important;
            margin-bottom: 10px;
        }

        body {
            font-size: 12px;
        }

        .main-content {
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>Report Filters
            @if(request()->filled(['date_from']) || request()->filled(['date_to']) || request()->filled(['movie_id']) || request()->filled(['status']))
                <span class="badge bg-primary ms-2">Filters Applied</span>
            @endif
        </h5>
        @if(request()->filled(['date_from']) || request()->filled(['date_to']) || request()->filled(['movie_id']) || request()->filled(['status']))
            <div class="text-muted small">
                @if(request('date_from') && request('date_to'))
                    {{ \Carbon\Carbon::parse(request('date_from'))->format('M j, Y') }} - {{ \Carbon\Carbon::parse(request('date_to'))->format('M j, Y') }}
                @elseif(request('date_from'))
                    From {{ \Carbon\Carbon::parse(request('date_from'))->format('M j, Y') }}
                @elseif(request('date_to'))
                    Until {{ \Carbon\Carbon::parse(request('date_to'))->format('M j, Y') }}
                @endif
                @if(request('movie_id'))
                    @php $movie = $movies->find(request('movie_id')); @endphp
                    | {{ $movie ? $movie->title : 'Unknown Movie' }}
                @endif
                @if(request('status'))
                    | {{ ucfirst(request('status')) }} only
                @endif
            </div>
        @endif
    </div>
    <div class="card-body">
        <form action="{{ route('dashboard.reports.bookings') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" max="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" max="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Movie</label>
                <select name="movie_id" class="form-select">
                    <option value="">All Movies</option>
                    @foreach($movies as $movie)
                        <option value="{{ $movie->id }}" {{ request('movie_id') == $movie->id ? 'selected' : '' }}>
                            {{ $movie->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('dashboard.reports.bookings') }}" class="btn btn-outline-secondary">Reset</a>
                <button type="submit" class="btn btn-primary" id="applyFiltersBtn">
                    <i class="fas fa-filter me-1"></i> Apply Filters
                </button>
                <div class="dropdown export-dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        <li><a class="dropdown-item" href="{{ route('dashboard.reports.bookings.export', request()->query()) }}"><i class="fas fa-file-excel me-2"></i> Export to Excel</a></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard.reports.bookings.print', request()->query()) }}" target="_blank"><i class="fas fa-print me-2"></i> Print Report</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="window.print(); return false;"><i class="fas fa-print me-2"></i> Print Current Page</a></li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('applyFiltersBtn').addEventListener('click', function(e) {
    e.preventDefault();

    const form = this.closest('form');
    const formData = new FormData(form);
    const params = new URLSearchParams();

    // Only add non-empty values to the URL
    for (let [key, value] of formData.entries()) {
        if (value && value.trim() !== '') {
            params.append(key, value);
        }
    }

    // Redirect with clean parameters
    const baseUrl = form.action;
    const queryString = params.toString();
    const finalUrl = queryString ? `${baseUrl}?${queryString}` : baseUrl;

    window.location.href = finalUrl;
});
</script>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card summary-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-uppercase">Total Bookings</h6>
                        <h4 class="metric">{{ number_format($totals['bookings_count']) }}</h4>
                    </div>
                    <div class="icon bg-primary-subtle text-primary">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-uppercase">Total Revenue</h6>
                        <h4 class="metric">${{ number_format($totals['revenue'], 2) }}</h4>
                    </div>
                    <div class="icon bg-success-subtle text-success">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-uppercase">Tickets Revenue</h6>
                        <h4 class="metric">${{ number_format($totals['tickets_revenue'], 2) }}</h4>
                    </div>
                    <div class="icon bg-info-subtle text-info">
                        <i class="fas fa-film"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-uppercase">Concessions Revenue</h6>
                        <h4 class="metric">${{ number_format($totals['food_revenue'] + $totals['drinks_revenue'], 2) }}</h4>
                    </div>
                    <div class="icon bg-warning-subtle text-warning">
                        <i class="fas fa-utensils"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Movies and Revenue Breakdown -->
<div class="row mb-4">
    <!-- Top Grossing Movies -->
    <div class="col-md-6">
        <div class="card top-movies-card h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Top Movies by Revenue</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Movie</th>
                                <th class="text-end">Bookings</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topMovies as $index => $movie)
                                <tr class="movie-row {{ $index < 3 ? 'top-'.($index+1) : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $movie->title }}</td>
                                    <td class="text-end">{{ number_format($movie->booking_count) }}</td>
                                    <td class="text-end">${{ number_format($movie->total_revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No movie data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Revenue Breakdown -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Revenue Distribution</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 200px;">
                    <canvas id="revenueBreakdownChart"></canvas>
                </div>
                
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-2" style="width: 12px; height: 12px;"></span>
                            <span>Tickets</span>
                        </div>
                        <span class="fw-bold">${{ number_format($totals['tickets_revenue'], 2) }} 
                            ({{ $totals['revenue'] > 0 ? number_format(($totals['tickets_revenue'] / $totals['revenue']) * 100, 1) : 0 }}%)</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2" style="width: 12px; height: 12px;"></span>
                            <span>Food</span>
                        </div>
                        <span class="fw-bold">${{ number_format($totals['food_revenue'], 2) }}
                            ({{ $totals['revenue'] > 0 ? number_format(($totals['food_revenue'] / $totals['revenue']) * 100, 1) : 0 }}%)</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info me-2" style="width: 12px; height: 12px;"></span>
                            <span>Drinks</span>
                        </div>
                        <span class="fw-bold">${{ number_format($totals['drinks_revenue'], 2) }}
                            ({{ $totals['revenue'] > 0 ? number_format(($totals['drinks_revenue'] / $totals['revenue']) * 100, 1) : 0 }}%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bookings Table -->
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Recent Bookings</h5>
        <span class="badge bg-primary">{{ $bookings->total() }} bookings</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover booking-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Booking #</th>
                        <th>Date & Time</th>
                        <th>Customer</th>
                        <th>Movie</th>
                        <th>Seats</th>
                        <th>Status</th>
                        <th class="text-end">Amount</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_number }}</td>
                            <td>{{ $booking->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div>{{ $booking->customer_name }}</div>
                                <div class="small text-muted">{{ $booking->customer_phone }}</div>
                            </td>
                            <td>
                                @if($booking->movieHall && $booking->movieHall->movie)
                                    <div>{{ $booking->movieHall->movie->title }}</div>
                                    <div class="small text-muted">{{ $booking->movieHall->hall ? $booking->movieHall->hall->name : 'Unknown Hall' }}</div>
                                @else
                                    <span class="text-muted">Unknown</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $seats = $booking->items()->where('item_type', 'ticket')->get();
                                    $seatCount = $seats->count();
                                @endphp
                                {{ $seatCount }} {{ \Illuminate\Support\Str::plural('seat', $seatCount) }}
                                <button type="button" class="btn btn-sm btn-link p-0 ms-1 view-seats" data-bs-toggle="collapse" data-bs-target="#seatDetails{{ $booking->id }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </td>
                            <td>
                                @if($booking->status == 'confirmed')
                                    <span class="badge bg-success status-badge">Confirmed</span>
                                @elseif($booking->status == 'pending')
                                    <span class="badge bg-warning status-badge">Pending</span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="badge bg-danger status-badge">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary status-badge">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold">${{ number_format($booking->total_amount, 2) }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('dashboard.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary print-booking" data-booking-id="{{ $booking->id }}">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="collapse booking-details-row" id="seatDetails{{ $booking->id }}">
                            <td colspan="8" class="pt-0">
                                <div class="px-3 py-2">
                                    <div class="mb-2"><strong>Seats:</strong> 
                                        @foreach($seats as $seatItem)
                                            <span class="badge bg-light text-dark border">{{ $seatItem->item_name }}</span>
                                        @endforeach
                                    </div>
                                    
                                    <div class="mb-2"><strong>Food & Drinks:</strong> 
                                        @php
                                            $concessions = $booking->items()->whereIn('item_type', ['food', 'drink'])->get();
                                        @endphp
                                        
                                        @if($concessions->count() > 0)
                                            @foreach($concessions as $item)
                                                <span class="badge bg-light text-dark border">
                                                    {{ $item->item_name }} × {{ $item->quantity }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No food or drinks ordered</span>
                                        @endif
                                    </div>
                                    
                                    @if($booking->notes)
                                        <div><strong>Notes:</strong> {{ $booking->notes }}</div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-ticket-alt fa-3x mb-3"></i>
                                    <p>No booking records found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bookings->hasPages())
        <div class="card-footer">
            {{ $bookings->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue breakdown chart
        const ctx = document.getElementById('revenueBreakdownChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Tickets', 'Food', 'Drinks'],
                datasets: [{
                    data: [
                        {{ $totals['tickets_revenue'] }}, 
                        {{ $totals['food_revenue'] }}, 
                        {{ $totals['drinks_revenue'] }}
                    ],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.8)',
                        'rgba(25, 135, 84, 0.8)',
                        'rgba(13, 202, 240, 0.8)'
                    ],
                    borderColor: [
                        'rgba(13, 110, 253, 1)',
                        'rgba(25, 135, 84, 1)',
                        'rgba(13, 202, 240, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += '$' + new Intl.NumberFormat('en-US', { 
                                    minimumFractionDigits: 2, 
                                    maximumFractionDigits: 2 
                                }).format(context.raw);
                                return label;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
        
        // Toggle seat details
        document.querySelectorAll('.view-seats').forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (icon.classList.contains('fa-chevron-down')) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        });

        // Handle print button click
        document.querySelectorAll('.print-booking').forEach(button => {
            button.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-booking-id');
                if (bookingId) {
                    // Open individual booking print page in new window
                    window.open(`/dashboard/bookings/${bookingId}/print`, '_blank');
                } else {
                    // Fallback to printing current page
                    window.print();
                }
            });
        });

        // Add print page title
        const originalTitle = document.title;
        window.addEventListener('beforeprint', function() {
            document.title = 'Booking Report - ' + new Date().toLocaleDateString();
        });

        window.addEventListener('afterprint', function() {
            document.title = originalTitle;
        });

        // Export functionality with loading states
        document.querySelectorAll('a[href*="export"]').forEach(link => {
            link.addEventListener('click', function() {
                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Exporting...';
                btn.classList.add('disabled');

                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('disabled');
                }, 3000);
            });
        });
    });
</script>
@endsection