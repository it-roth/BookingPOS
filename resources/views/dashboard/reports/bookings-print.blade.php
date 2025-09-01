<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Report - {{ date('Y-m-d') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .filters {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .filters h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 15px;
        }
        
        .summary-card {
            flex: 1;
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        
        .summary-card h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
        }
        
        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        td {
            font-size: 11px;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status.confirmed { background: #d4edda; color: #155724; }
        .status.pending { background: #fff3cd; color: #856404; }
        .status.cancelled { background: #f8d7da; color: #721c24; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Cinema Booking Report</h1>
        <p>Generated on {{ date('F j, Y \a\t g:i A') }}</p>
        @if(($request->has('date_from') && $request->date_from) || ($request->has('date_to') && $request->date_to))
            <p>
                Period:
                @if($request->has('date_from') && $request->date_from)
                    {{ \Carbon\Carbon::parse($request->date_from)->format('M j, Y') }}
                @else
                    Beginning
                @endif
                to
                @if($request->has('date_to') && $request->date_to)
                    {{ \Carbon\Carbon::parse($request->date_to)->format('M j, Y') }}
                @else
                    Present
                @endif
            </p>
        @else
            <p>Period: All available data</p>
        @endif
    </div>

    @if($request->filled('date_from') || $request->filled('date_to') || $request->filled('movie_id') || $request->filled('status'))
    <div class="filters">
        <h3>Applied Filters:</h3>
        @if($request->filled('date_from'))
            <p><strong>From Date:</strong> {{ \Carbon\Carbon::parse($request->date_from)->format('M j, Y') }}</p>
        @endif
        @if($request->filled('date_to'))
            <p><strong>To Date:</strong> {{ \Carbon\Carbon::parse($request->date_to)->format('M j, Y') }}</p>
        @endif
        @if($request->filled('movie_id'))
            @php $movie = \App\Models\Movie::find($request->movie_id); @endphp
            <p><strong>Movie:</strong> {{ $movie ? $movie->title : 'Unknown' }}</p>
        @endif
        @if($request->filled('status'))
            <p><strong>Status:</strong> {{ ucfirst($request->status) }}</p>
        @endif
    </div>
    @else
    <div class="filters">
        <h3>Report Scope:</h3>
        <p><strong>Period:</strong> All available data</p>
        <p><strong>Movies:</strong> All movies</p>
        <p><strong>Status:</strong> All statuses</p>
    </div>
    @endif

    <div class="summary-cards">
        <div class="summary-card">
            <h4>Total Bookings</h4>
            <div class="value">{{ number_format($totals['bookings_count']) }}</div>
        </div>
        <div class="summary-card">
            <h4>Total Revenue</h4>
            <div class="value">${{ number_format($totals['revenue'], 2) }}</div>
        </div>
        <div class="summary-card">
            <h4>Tickets Revenue</h4>
            <div class="value">${{ number_format($totals['tickets_revenue'], 2) }}</div>
        </div>
        <div class="summary-card">
            <h4>Food & Drinks</h4>
            <div class="value">${{ number_format($totals['food_revenue'] + $totals['drinks_revenue'], 2) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Booking #</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Movie</th>
                <th>Hall</th>
                <th>Seats</th>
                <th>Status</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->booking_number }}</td>
                    <td>{{ $booking->created_at->format('M j, Y H:i') }}</td>
                    <td>
                        {{ $booking->customer_name }}<br>
                        <small>{{ $booking->customer_phone }}</small>
                    </td>
                    <td>
                        @if($booking->movieHall && $booking->movieHall->movie)
                            {{ $booking->movieHall->movie->title }}
                        @else
                            Unknown
                        @endif
                    </td>
                    <td>
                        @if($booking->movieHall && $booking->movieHall->hall)
                            {{ $booking->movieHall->hall->name }}
                        @else
                            Unknown
                        @endif
                    </td>
                    <td>
                        @php
                            $seats = $booking->items()->where('item_type', 'ticket')->get();
                            $seatNames = $seats->pluck('item_name')->join(', ');
                        @endphp
                        {{ $seatNames ?: 'No seats' }}
                    </td>
                    <td class="text-center">
                        <span class="status {{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                    <td class="text-right">${{ number_format($booking->total_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No bookings found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the Cinema Management System</p>
        <p>© {{ date('Y') }} Cinema Management System. All rights reserved.</p>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
