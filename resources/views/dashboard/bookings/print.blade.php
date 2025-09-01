<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking #{{ $booking->booking_number }} - Print</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #333;
        }

        .header p {
            font-size: 14px;
            color: #666;
        }

        .booking-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-section h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status.confirmed {
            background-color: #d4edda;
            color: #155724;
        }

        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status.cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .items-section {
            margin-bottom: 30px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .items-table th,
        .items-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .items-table .text-right {
            text-align: right;
        }

        .items-table .text-center {
            text-align: center;
        }

        .total-section {
            border-top: 2px solid #333;
            padding-top: 15px;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .total-row.grand-total {
            font-size: 16px;
            font-weight: bold;
            border-top: 1px solid #ddd;
            padding-top: 8px;
            margin-top: 8px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .container {
                max-width: none;
                margin: 0;
                padding: 15px;
            }
            
            .header {
                margin-bottom: 20px;
                padding-bottom: 15px;
            }
            
            .booking-info {
                margin-bottom: 20px;
            }
            
            .items-section {
                margin-bottom: 20px;
            }
            
            .footer {
                margin-top: 30px;
                padding-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Cinema Management System</h1>
            <p>Booking Receipt</p>
        </div>

        <!-- Booking Information -->
        <div class="booking-info">
            <div class="info-section">
                <h3>Booking Details</h3>
                <div class="info-row">
                    <span class="info-label">Booking Number:</span>
                    <span class="info-value">{{ $booking->booking_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date & Time:</span>
                    <span class="info-value">{{ $booking->created_at ? $booking->created_at->format('M d, Y H:i') : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status {{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                    </span>
                </div>
            </div>

            <div class="info-section">
                <h3>Customer Information</h3>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $booking->customer_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $booking->customer_phone }}</span>
                </div>
                @if($booking->customer_email)
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $booking->customer_email }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Movie Information -->
        @if($booking->movieHall && $booking->movieHall->movie)
        <div class="info-section">
            <h3>Movie & Showtime</h3>
            <div class="info-row">
                <span class="info-label">Movie:</span>
                <span class="info-value">{{ $booking->movieHall->movie->title }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Hall:</span>
                <span class="info-value">{{ $booking->movieHall->hall ? $booking->movieHall->hall->name : 'Unknown Hall' }}</span>
            </div>
            @if($booking->movieHall->showtime)
            <div class="info-row">
                <span class="info-label">Showtime:</span>
                <span class="info-value">{{ $booking->movieHall->showtime ? \Carbon\Carbon::parse($booking->movieHall->showtime)->format('M d, Y H:i') : 'N/A' }}</span>
            </div>
            @endif
        </div>
        @endif

        <!-- Items -->
        <div class="items-section">
            <h3>Booking Items</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Type</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($booking->items as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ ucfirst($item->item_type) }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">${{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No items found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Total Section -->
            <div class="total-section">
                @php
                    $ticketTotal = $booking->items->where('item_type', 'ticket')->sum('total_price');
                    $foodTotal = $booking->items->where('item_type', 'food')->sum('total_price');
                    $drinkTotal = $booking->items->where('item_type', 'drink')->sum('total_price');
                @endphp
                
                @if($ticketTotal > 0)
                <div class="total-row">
                    <span>Tickets Subtotal:</span>
                    <span>${{ number_format($ticketTotal, 2) }}</span>
                </div>
                @endif
                
                @if($foodTotal > 0)
                <div class="total-row">
                    <span>Food Subtotal:</span>
                    <span>${{ number_format($foodTotal, 2) }}</span>
                </div>
                @endif
                
                @if($drinkTotal > 0)
                <div class="total-row">
                    <span>Drinks Subtotal:</span>
                    <span>${{ number_format($drinkTotal, 2) }}</span>
                </div>
                @endif
                
                <div class="total-row grand-total">
                    <span>Grand Total:</span>
                    <span>${{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing our cinema!</p>
            <p>This receipt was generated on {{ now()->format('M d, Y H:i') }}</p>
            <p>© {{ date('Y') }} Cinema Management System. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
