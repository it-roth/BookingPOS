@extends('layouts.app')

@section('title', 'Dashboard - TOS-MERL RG')

@section('page-title', 'Dashboard Overview')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.css">
@endsection

@section('content')
<!-- Dashboard Overview -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card welcome-card">
            <div class="card-body p-3 p-md-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="fw-bold mb-3">Welcome to TOS-MERL RG System</h4>
                        <p class="text-muted">Manage your operations efficiently with our comprehensive management system. Here's an overview of your current data.</p>
                        <div class="d-flex flex-column flex-sm-row gap-2 mt-3">
                            <a href="#quick-actions" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1 me-md-2"></i> Quick Actions
                            </a>
                            <a href="{{ route('dashboard.reports.bookings') }}" class="btn btn-outline-light btn-sm" style="background-color: white; border-color: white; color: black;">
                                <i class="fas fa-chart-bar me-1 me-md-2"></i> View Reports
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 d-none d-md-block text-end">
                        <img src="https://cdn-icons-png.flaticon.com/512/3418/3418886.png" alt="TOS-MERL RG" class="img-fluid" style="max-width: 220px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-2 g-md-3 mb-4">
    <div class="col-6 col-md-4 col-xl-3">
        <div class="stat-card h-100">
            <div class="stat-icon">
                <i class="fas fa-film"></i>
            </div>
            <h6 class="stat-title">Movies</h6>
            <h3 class="stat-value">{{ \App\Models\Movie::count() }}</h3>
            <div class="d-flex justify-content-between align-items-center mt-auto">
                <small class="text-muted d-none d-sm-block">{{ \App\Models\Movie::where('is_showing', true)->count() }} showing now</small>
                <small class="text-muted d-sm-none">{{ \App\Models\Movie::where('is_showing', true)->count() }} showing</small>
                <a href="{{ route('dashboard.movies') }}" class="text-primary">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-3">
        <div class="stat-card h-100" style="border-left-color: var(--success);">
            <div class="stat-icon" style="background-color: rgba(58, 196, 125, 0.1); color: var(--success);">
                <i class="fas fa-building"></i>
            </div>
            <h6 class="stat-title">Halls</h6>
            <h3 class="stat-value">{{ \App\Models\Hall::count() }}</h3>
            <div class="d-flex justify-content-between align-items-center mt-auto">
                <small class="text-muted d-none d-sm-block">{{ \App\Models\Hall::where('is_active', true)->count() }} active</small>
                <small class="text-muted d-sm-none">{{ \App\Models\Hall::where('is_active', true)->count() }} active</small>
                <a href="{{ route('dashboard.halls') }}" class="text-success">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-3">
        <div class="stat-card h-100" style="border-left-color: var(--info);">
            <div class="stat-icon" style="background-color: rgba(22, 170, 255, 0.1); color: var(--info);">
                <i class="fas fa-chair"></i>
            </div>
            <h6 class="stat-title">Seats</h6>
            <h3 class="stat-value">{{ \App\Models\Seat::count() }}</h3>
            <div class="d-flex justify-content-between align-items-center mt-auto">
                <small class="text-muted d-none d-sm-block">{{ \App\Models\Seat::where('is_available', true)->count() }} available</small>
                <small class="text-muted d-sm-none">{{ \App\Models\Seat::where('is_available', true)->count() }} avail</small>
                <a href="{{ route('dashboard.seats') }}" class="text-info">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-3">
        <div class="stat-card h-100" style="border-left-color: var(--warning);">
            <div class="stat-icon" style="background-color: rgba(247, 185, 36, 0.1); color: var(--warning);">
                <i class="fas fa-utensils"></i>
            </div>
            <h6 class="stat-title">Concessions</h6>
            <h3 class="stat-value">{{ \App\Models\FoodItem::count() + \App\Models\Drink::count() }}</h3>
            <div class="d-flex justify-content-between align-items-center mt-auto">
                <small class="text-muted d-none d-sm-block">{{ \App\Models\FoodItem::count() }} food, {{ \App\Models\Drink::count() }} drinks</small>
                <small class="text-muted d-sm-none">{{ \App\Models\FoodItem::count() + \App\Models\Drink::count() }} items</small>
                <a href="{{ route('dashboard.food-items') }}" class="text-warning">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Analytics and Quick Actions -->
<div class="row mb-4">
    <!-- Revenue Chart -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <h5 class="card-title mb-0">Revenue Performance</h5>
                <div class="btn-group btn-group-sm w-100 w-sm-auto">
                    <button type="button" class="btn btn-outline-primary active" data-period="monthly">Monthly</button>
                    <button type="button" class="btn btn-outline-primary" data-period="quarterly">Quarterly</button>
                    <button type="button" class="btn btn-outline-primary" data-period="yearly">Yearly</button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 280px;">
                    <canvas id="revenueChart"></canvas>
                </div>
                <div class="chart-stats row mt-4 text-center">
                    <div class="col-6 col-md-3 stat-item mb-2 mb-md-0">
                        <p class="text-muted mb-1 small">Total Revenue</p>
                        <h6 class="mb-0 fw-bold d-md-none">$245.8K</h6>
                        <h5 class="mb-0 fw-bold d-none d-md-block">$245,800</h5>
                    </div>
                    <div class="col-6 col-md-3 stat-item mb-2 mb-md-0">
                        <p class="text-muted mb-1 small">Average</p>
                        <h6 class="mb-0 fw-bold d-md-none">$20.5K</h6>
                        <h5 class="mb-0 fw-bold d-none d-md-block">$20,483</h5>
                    </div>
                    <div class="col-6 col-md-3 stat-item mb-2 mb-md-0">
                        <p class="text-muted mb-1 small">Highest Month</p>
                        <h6 class="mb-0 fw-bold d-md-none">Jul $24.5K</h6>
                        <h5 class="mb-0 fw-bold d-none d-md-block">Jul ($24,500)</h5>
                    </div>
                    <div class="col-6 col-md-3 stat-item">
                        <p class="text-muted mb-1 small">Growth</p>
                        <h6 class="mb-0 fw-bold text-success d-md-none">+8.2%</h6>
                        <h5 class="mb-0 fw-bold text-success d-none d-md-block">+8.2%</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Seat Distribution & Quick Stats -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Seat Distribution</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-center mb-3 seat-chart-container">
                    <div style="height: 150px; width: 150px;">
                        <canvas id="seatChart"></canvas>
                    </div>
                </div>
                
                <div class="mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-2" style="width: 12px; height: 12px;"></span>
                            <span>Regular</span>
                        </div>
                        <span class="fw-bold">{{ \App\Models\Seat::where('type', 'regular')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2" style="width: 12px; height: 12px;"></span>
                            <span>Premium</span>
                        </div>
                        <span class="fw-bold">{{ \App\Models\Seat::where('type', 'premium')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-danger me-2" style="width: 12px; height: 12px;"></span>
                            <span>VIP</span>
                        </div>
                        <span class="fw-bold">{{ \App\Models\Seat::where('type', 'vip')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card" id="quick-actions">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('movies.create') }}" class="actions-container d-block text-decoration-none">
                            <i class="fas fa-film"></i>
                            <p>Add Movie</p>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('halls.create') }}" class="actions-container d-block text-decoration-none">
                            <i class="fas fa-building"></i>
                            <p>Add Hall</p>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('dashboard.movieHallAssignments') }}" class="actions-container d-block text-decoration-none">
                            <i class="fas fa-calendar-alt"></i>
                            <p>Showtimes</p>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('seats.bulkCreate') }}" class="actions-container d-block text-decoration-none">
                            <i class="fas fa-th"></i>
                            <p>Bulk Seats</p>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('dashboard.pos') }}" class="actions-container d-block text-decoration-none" style="background-color: rgba(255, 193, 7, 0.1);">
                            <i class="fas fa-cash-register text-warning"></i>
                            <p>POS System</p>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('dashboard.reports.bookings') }}" class="actions-container d-block text-decoration-none" style="background-color: rgba(13, 110, 253, 0.1);">
                            <i class="fas fa-chart-bar text-primary"></i>
                            <p>Booking Reports</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Data Tables -->
<div class="row">
    <!-- Recent Movies -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <h5 class="card-title mb-0">Recent Movies</h5>
                <a href="{{ route('dashboard.movies') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover recent-table mb-0">
                        <thead>
                            <tr>
                                <th>Movie</th>
                                <th class="d-none d-md-table-cell">Genre</th>
                                <th class="d-none d-lg-table-cell">Duration</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $movies = \App\Models\Movie::latest()->take(5)->get();
                            @endphp

                            @forelse($movies as $movie)
                                <tr>
                                    <td class="fw-medium" data-label="Movie">
                                        <div>{{ $movie->title }}</div>
                                        <small class="text-muted d-md-none">
                                            {{ $movie->genre }} • {{ $movie->duration }} min
                                        </small>
                                    </td>
                                    <td class="d-none d-md-table-cell" data-label="Genre">{{ $movie->genre }}</td>
                                    <td class="d-none d-lg-table-cell" data-label="Duration">{{ $movie->duration }} min</td>
                                    <td data-label="Status">
                                        @if($movie->is_showing)
                                            <span class="badge bg-success">Showing</span>
                                        @else
                                            <span class="badge bg-secondary">Not Showing</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No movies found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hall Status -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <h5 class="card-title mb-0">Hall Status</h5>
                <a href="{{ route('dashboard.halls') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover recent-table mb-0">
                        <thead>
                            <tr>
                                <th>Hall</th>
                                <th class="d-none d-md-table-cell">Type</th>
                                <th class="d-none d-lg-table-cell">Capacity</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $halls = \App\Models\Hall::with('seats')->latest()->take(5)->get();
                            @endphp
                            
                            @forelse($halls as $hall)
                                <tr>
                                    <td class="fw-medium" data-label="Hall">
                                        <div>{{ $hall->name }}</div>
                                        <small class="text-muted d-md-none">
                                            {{ $hall->hall_type }} • {{ $hall->capacity }} seats •
                                            @if($hall->is_active)
                                                <span class="text-success">Active</span>
                                            @else
                                                <span class="text-secondary">Inactive</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td class="d-none d-md-table-cell" data-label="Type">{{ $hall->hall_type }}</td>
                                    <td class="d-none d-lg-table-cell" data-label="Capacity">{{ $hall->capacity }}</td>
                                    <td class="d-none d-md-table-cell" data-label="Status">
                                        @if($hall->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">No halls found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        
        // Create gradient
        const gradientFill = revenueCtx.createLinearGradient(0, 0, 0, 280);
        gradientFill.addColorStop(0, 'rgba(63, 106, 216, 0.25)');
        gradientFill.addColorStop(1, 'rgba(63, 106, 216, 0.01)');
        
        // Data sets for different periods
        const chartData = {
            monthly: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                data: [12500, 15000, 17800, 16200, 19000, 22000, 24500, 23000, 21500, 20000, 18500, 22800]
            },
            quarterly: {
                labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                data: [45300, 57200, 69000, 61300]
            },
            yearly: {
                labels: ['2019', '2020', '2021', '2022', '2023'],
                data: [170000, 155000, 210000, 232000, 245800]
            }
        };
        
        // Initialize the chart with monthly data
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: chartData.monthly.labels,
                datasets: [{
                    label: 'Revenue',
                    data: chartData.monthly.data,
                    backgroundColor: gradientFill,
                    borderColor: 'rgba(63, 106, 216, 1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgba(63, 106, 216, 1)',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(63, 106, 216, 1)',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear',
                        from: 0.4,
                        to: 0.5,
                        loop: true
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [5, 5],
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            },
                            padding: 10,
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            padding: 10,
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 10,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        bodySpacing: 5,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        
        // Handle period change buttons
        document.querySelectorAll('.btn-group .btn').forEach(button => {
            button.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.btn-group .btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                // Get selected period
                const period = this.getAttribute('data-period');
                
                // Update chart data
                revenueChart.data.labels = chartData[period].labels;
                revenueChart.data.datasets[0].data = chartData[period].data;
                revenueChart.update();
            });
        });
        
        // Seat Distribution Chart
        const seatCtx = document.getElementById('seatChart').getContext('2d');
        
        // Get seat data from PHP
        const regularSeats = {{ \App\Models\Seat::where('type', 'regular')->count() ?? 0 }};
        const premiumSeats = {{ \App\Models\Seat::where('type', 'premium')->count() ?? 0 }};
        const vipSeats = {{ \App\Models\Seat::where('type', 'vip')->count() ?? 0 }};
        
        // Check if we have any data
        const totalSeats = regularSeats + premiumSeats + vipSeats;
        
        // If no data, show a placeholder
        if (totalSeats === 0) {
            // Display a placeholder message in the chart area
            document.querySelector('.seat-chart-container').innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-chart-pie fa-3x mb-3 opacity-50"></i>
                    <p>No seat data available</p>
                    <a href="{{ route('seats.create') }}" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="fas fa-plus me-1"></i> Add Seats
                    </a>
                </div>
            `;
        } else {
            // Create the chart with data
            new Chart(seatCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Regular', 'Premium', 'VIP'],
                    datasets: [{
                        data: [regularSeats, premiumSeats, vipSeats],
                        backgroundColor: [
                            'rgba(63, 106, 216, 1)',
                            'rgba(58, 196, 125, 1)',
                            'rgba(217, 37, 80, 1)'
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    layout: {
                        padding: 5
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            padding: 8,
                            titleFont: {
                                size: 13
                            },
                            bodyFont: {
                                size: 12
                            },
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const percentage = totalSeats > 0 
                                        ? Math.round((value / totalSeats) * 100) 
                                        : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection 