@extends('layouts.app')

@section('title', 'Booking Details')

@section('page-title', 'Booking Details')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Booking #{{ $booking->booking_number ?? 'N/A' }}</h5>
                    <div>
                        <a href="{{ route('dashboard.reports.bookings') }}" class="btn btn-outline-primary btn-sm ms-2">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Booking Status -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Status</h6>
                                @if($booking->status == 'confirmed')
                                    <span class="badge bg-success">Confirmed</span>
                                @elseif($booking->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </div>
                            <div class="text-end">
                                <h6 class="text-muted mb-1">Booking Date</h6>
                                <p class="mb-0">{{ $booking->created_at ? $booking->created_at->format('M d, Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Movie & Showtime Details -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">Movie & Showtime</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Movie:</strong> {{ $booking->movieHall && $booking->movieHall->movie ? $booking->movieHall->movie->title : 'N/A' }}</p>
                                <p class="mb-1"><strong>Hall:</strong> {{ $booking->movieHall && $booking->movieHall->hall ? $booking->movieHall->hall->name : 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Showtime:</strong> {{ $booking->movieHall && $booking->movieHall->showtime ? \Carbon\Carbon::parse($booking->movieHall->showtime)->format('M d, Y H:i') : 'N/A' }}</p>
                                <p class="mb-1"><strong>Duration:</strong> {{ $booking->movieHall && $booking->movieHall->movie ? $booking->movieHall->movie->duration . ' minutes' : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">Customer Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Name:</strong> {{ $booking->customer_name ?: 'N/A' }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $booking->customer_email ?: 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Phone:</strong> {{ $booking->customer_phone ?: 'N/A' }}</p>
                                @if($booking->notes)
                                    <p class="mb-1"><strong>Notes:</strong> {{ $booking->notes }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Booking Items -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">Booking Items</h6>
                        
                        <!-- Tickets -->
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Tickets</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Seat</th>
                                            <th class="text-end">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($booking->items()->where('item_type', 'ticket')->get() as $ticket)
                                            <tr>
                                                <td>{{ $ticket->item_name }}</td>
                                                <td class="text-end">${{ number_format($ticket->unit_price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Food & Drinks -->
                        @php
                            $concessions = $booking->items()->whereIn('item_type', ['food', 'drink'])->get();
                        @endphp
                        @if($concessions->count() > 0)
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Food & Drinks</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Item</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-end">Unit Price</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($concessions as $item)
                                                <tr>
                                                    <td>{{ $item->item_name }}</td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                                    <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Total -->
                        <div class="border-top pt-3 mt-3">
                            <div class="row">
                                <div class="col-md-6 offset-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Total Amount:</th>
                                            <td class="text-end"><strong>${{ number_format($booking->total_amount, 2) }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush