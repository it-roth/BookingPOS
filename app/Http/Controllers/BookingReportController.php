<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class BookingReportController extends Controller
{
    /**
     * Display a list of all bookings with financial summary
     */
    public function index(Request $request)
    {
        $query = Booking::with(['movieHall.movie', 'movieHall.hall', 'items']);
        
        // Apply filters if present and not empty
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('movie_id')) {
            $query->whereHas('movieHall', function($q) use ($request) {
                $q->where('movie_id', $request->movie_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Get bookings with pagination
        $bookings = $query->latest()->paginate(15);
        
        // Calculate financial summaries
        $totals = [
            'bookings_count' => $bookings->total(),
            'revenue' => Booking::when($request->has('date_from'), function($q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->has('date_to'), function($q) use ($request) {
                return $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->has('movie_id'), function($q) use ($request) {
                return $q->whereHas('movieHall', function($sq) use ($request) {
                    $sq->where('movie_id', $request->movie_id);
                });
            })
            ->when($request->has('status'), function($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->sum('total_amount'),
            
            // Breakdown by item type
            'tickets_revenue' => BookingItem::where('item_type', 'ticket')
                ->whereHas('booking', function($q) use ($request) {
                    $this->applyBookingFilters($q, $request);
                })
                ->sum('subtotal'),
                
            'food_revenue' => BookingItem::where('item_type', 'food')
                ->whereHas('booking', function($q) use ($request) {
                    $this->applyBookingFilters($q, $request);
                })
                ->sum('subtotal'),
                
            'drinks_revenue' => BookingItem::where('item_type', 'drink')
                ->whereHas('booking', function($q) use ($request) {
                    $this->applyBookingFilters($q, $request);
                })
                ->sum('subtotal'),
        ];
        
        // Get top movies by revenue
        $topMovies = DB::table('bookings')
            ->join('movie_halls', 'bookings.movie_hall_id', '=', 'movie_halls.id')
            ->join('movies', 'movie_halls.movie_id', '=', 'movies.id')
            ->select('movies.id', 'movies.title', DB::raw('SUM(bookings.total_amount) as total_revenue'), DB::raw('COUNT(bookings.id) as booking_count'))
            ->when($request->filled('date_from'), function($q) use ($request) {
                return $q->whereDate('bookings.created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function($q) use ($request) {
                return $q->whereDate('bookings.created_at', '<=', $request->date_to);
            })
            ->groupBy('movies.id', 'movies.title')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();
        
        // Get movies for filter dropdown
        $movies = Movie::orderBy('title')->get();

        return view('dashboard.reports.bookings', compact('bookings', 'totals', 'topMovies', 'movies'));
    }

    /**
     * Export bookings to Excel
     */
    public function exportExcel(Request $request)
    {
        $query = Booking::with(['movieHall.movie', 'movieHall.hall', 'items']);

        // Apply same filters as index method
        $this->applyFilters($query, $request);

        $bookings = $query->latest()->get();

        // Create CSV content
        $csvData = [];
        $csvData[] = [
            'Booking Number',
            'Date',
            'Customer Name',
            'Customer Phone',
            'Movie',
            'Hall',
            'Seats',
            'Status',
            'Total Amount',
            'Tickets Revenue',
            'Food Revenue',
            'Drinks Revenue'
        ];

        foreach ($bookings as $booking) {
            $seats = $booking->items()->where('item_type', 'ticket')->get();
            $seatNames = $seats->pluck('item_name')->join(', ');

            $ticketsRevenue = $booking->items()->where('item_type', 'ticket')->sum('subtotal');
            $foodRevenue = $booking->items()->where('item_type', 'food')->sum('subtotal');
            $drinksRevenue = $booking->items()->where('item_type', 'drink')->sum('subtotal');

            $csvData[] = [
                $booking->booking_number,
                $booking->created_at->format('Y-m-d H:i:s'),
                $booking->customer_name,
                $booking->customer_phone,
                $booking->movieHall && $booking->movieHall->movie ? $booking->movieHall->movie->title : 'Unknown',
                $booking->movieHall && $booking->movieHall->hall ? $booking->movieHall->hall->name : 'Unknown',
                $seatNames,
                ucfirst($booking->status),
                $booking->total_amount,
                $ticketsRevenue,
                $foodRevenue,
                $drinksRevenue
            ];
        }

        // Generate CSV
        $filename = 'booking_report_' . date('Y-m-d_H-i-s') . '.csv';

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Get printable report data
     */
    public function printReport(Request $request)
    {
        $query = Booking::with(['movieHall.movie', 'movieHall.hall', 'items']);

        // Apply same filters as index method
        $this->applyFilters($query, $request);

        $bookings = $query->latest()->get();

        // Calculate totals for print
        $totals = $this->calculateTotals($request);

        return view('dashboard.reports.bookings-print', compact('bookings', 'totals', 'request'));
    }

    /**
     * Apply filters to query
     */
    private function applyFilters($query, $request)
    {
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('movie_id')) {
            $query->whereHas('movieHall', function($q) use ($request) {
                $q->where('movie_id', $request->movie_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    /**
     * Calculate totals for reports
     */
    private function calculateTotals($request)
    {
        return [
            'bookings_count' => Booking::when($request->filled('date_from'), function($q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function($q) use ($request) {
                return $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->filled('movie_id'), function($q) use ($request) {
                return $q->whereHas('movieHall', function($sq) use ($request) {
                    $sq->where('movie_id', $request->movie_id);
                });
            })
            ->when($request->filled('status'), function($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->count(),

            'revenue' => Booking::when($request->filled('date_from'), function($q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function($q) use ($request) {
                return $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->filled('movie_id'), function($q) use ($request) {
                return $q->whereHas('movieHall', function($sq) use ($request) {
                    $sq->where('movie_id', $request->movie_id);
                });
            })
            ->when($request->filled('status'), function($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->sum('total_amount'),

            'tickets_revenue' => BookingItem::where('item_type', 'ticket')
                ->whereHas('booking', function($q) use ($request) {
                    $this->applyBookingFilters($q, $request);
                })
                ->sum('subtotal'),

            'food_revenue' => BookingItem::where('item_type', 'food')
                ->whereHas('booking', function($q) use ($request) {
                    $this->applyBookingFilters($q, $request);
                })
                ->sum('subtotal'),

            'drinks_revenue' => BookingItem::where('item_type', 'drink')
                ->whereHas('booking', function($q) use ($request) {
                    $this->applyBookingFilters($q, $request);
                })
                ->sum('subtotal'),
        ];
    }

    /**
     * Helper method to apply booking filters
     */
    private function applyBookingFilters($query, $request)
    {
        return $query->when($request->filled('date_from'), function($q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function($q) use ($request) {
                return $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->filled('movie_id'), function($q) use ($request) {
                return $q->whereHas('movieHall', function($sq) use ($request) {
                    $sq->where('movie_id', $request->movie_id);
                });
            })
            ->when($request->filled('status'), function($q) use ($request) {
                return $q->where('status', $request->status);
            });
    }
}