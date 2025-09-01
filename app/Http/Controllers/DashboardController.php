<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\FoodItem;
use App\Models\Drink;
use App\Models\MovieHall;
use App\Models\Booking;
use App\Models\BookingItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index()
    {
        return view('dashboard.index');
    }
    
    /**
     * Display the movies dashboard.
     */
    public function movies(Request $request)
    {
        $query = Movie::latest();
        
        // Filter by is_showing if specified
        if ($request->has('is_showing')) {
            $query->where('is_showing', $request->is_showing == 'true');
        }
        
        $movies = $query->paginate(5);
        
        // Maintain the filters in pagination links
        $movies->appends($request->only(['is_showing']));
        
        return view('dashboard.movies', compact('movies'));
    }
    
    /**
     * Display the halls dashboard.
     */
    public function halls(Request $request)
    {
        $query = Hall::with('seats');
        
        // Filter by hall_type if specified
        if ($request->has('hall_type') && $request->hall_type) {
            $query->where('hall_type', $request->hall_type);
        }
        
        // Filter by is_active if specified
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active == 'true');
        }
        
        $halls = $query->paginate(5);
        
        // Maintain the filters in pagination links
        $halls->appends($request->only(['hall_type', 'is_active']));
        
        return view('dashboard.halls', compact('halls'));
    }
    
    /**
     * Display the seats dashboard.
     */
    public function seats()
    {
        $seats = Seat::with('hall')->paginate(10);
        $halls = Hall::all();
        return view('dashboard.seats', compact('seats', 'halls'));
    }
    
    /**
     * Display the food items dashboard.
     */
    public function foodItems(Request $request)
    {
        $query = FoodItem::latest();
        
        // Filter by category if specified
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }
        
        $foodItems = $query->paginate(5);
        
        // Maintain the filters in pagination links
        $foodItems->appends($request->only(['category']));
        
        return view('dashboard.food-items', compact('foodItems'));
    }
    
    /**
     * Display the drinks dashboard.
     */
    public function drinks(Request $request)
    {
        $query = Drink::latest();
        
        // Filter by category if specified
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }
        
        // Filter by size if specified
        if ($request->has('size') && $request->size) {
            $query->where('size', $request->size);
        }
        
        $drinks = $query->paginate(5);
        
        // Maintain the filters in pagination links
        $drinks->appends($request->only(['category', 'size']));
        
        return view('dashboard.drinks', compact('drinks'));
    }
    
    /**
     * Display the POS system dashboard.
     */
    public function pos()
    {
        $movies = Movie::where('is_showing', true)->paginate(6);
        $foodItems = FoodItem::where('is_available', true)->get();
        $drinks = Drink::where('is_available', true)->get();
        
        return view('dashboard.pos', compact('movies', 'foodItems', 'drinks'));
    }
    
    /**
     * Get halls where a specific movie is showing.
     */
    public function getMovieHalls(Movie $movie)
    {
        $movieHalls = MovieHall::with(['hall'])
            ->where('movie_id', $movie->id)
            ->where('is_active', true)
            ->where('showtime', '>', now())
            ->orderBy('showtime')
            ->get();
            
        return response()->json($movieHalls);
    }
    
    /**
     * Get seats for a specific hall.
     */
    public function getHallSeats(Hall $hall, Request $request)
    {
        $seats = Seat::where('hall_id', $hall->id)
            ->where('is_available', true)
            ->get();
            
        // Get booked seats for active shows in this hall
        $bookedSeats = DB::table('booking_items')
            ->join('bookings', 'bookings.id', '=', 'booking_items.booking_id')
            ->join('movie_halls', 'movie_halls.id', '=', 'bookings.movie_hall_id')
            ->where('movie_halls.hall_id', $hall->id)
            ->where('movie_halls.showtime', '>', now())
            ->where('booking_items.item_type', 'ticket')
            ->pluck('booking_items.item_id')
            ->toArray();
        
        // Add booking status and price to each seat
        foreach ($seats as $seat) {
            $seat->is_booked = in_array($seat->id, $bookedSeats);

            // Calculate ticket price: base price + additional charge
            $baseTicketPrice = 10.00; // Default base ticket price
            $seat->calculated_price = floatval($baseTicketPrice + $seat->additional_charge);
        }
            
        return response()->json($seats);
    }
    
    /**
     * Book a ticket with food and drink items.
     */
    public function bookTicket(Request $request)
    {
        $request->validate([
            'movie_hall_id' => 'required|exists:movie_halls,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'selected_seats' => 'required|array',
            'selected_seats.*' => 'exists:seats,id',
            'food_items' => 'nullable|array',
            'food_items.*.id' => 'exists:food_items,id',
            'food_items.*.quantity' => 'integer|min:1',
            'drinks' => 'nullable|array',
            'drinks.*.id' => 'exists:drinks,id',
            'drinks.*.quantity' => 'integer|min:1',
        ]);
        
        try {
            DB::beginTransaction();
            
            $movieHall = MovieHall::findOrFail($request->movie_hall_id);
            
            // Create booking
            $booking = new Booking();
            $booking->booking_number = Booking::generateBookingNumber();
            $booking->movie_hall_id = $movieHall->id;
            $booking->customer_name = $request->customer_name;
            $booking->customer_email = $request->customer_email;
            $booking->customer_phone = $request->customer_phone;
            $booking->status = 'confirmed';
            $booking->total_amount = 0; // Will calculate below
            $booking->save();
            
            $totalAmount = 0;
            
            // Add seats/tickets
            foreach ($request->selected_seats as $seatId) {
                $seat = Seat::findOrFail($seatId);
                
                // Check if seat is already booked
                $isBooked = BookingItem::join('bookings', 'bookings.id', '=', 'booking_items.booking_id')
                    ->join('movie_halls', 'movie_halls.id', '=', 'bookings.movie_hall_id')
                    ->where('movie_halls.hall_id', $seat->hall_id)
                    ->where('movie_halls.showtime', '=', $movieHall->showtime)
                    ->where('booking_items.item_type', 'ticket')
                    ->where('booking_items.item_id', $seatId)
                    ->exists();
                    
                if ($isBooked) {
                    throw new \Exception("Seat {$seat->row}-{$seat->number} is already booked.");
                }
                
                // Calculate ticket price: base price + additional charge
                $baseTicketPrice = 10.00; // Default base ticket price
                $ticketPrice = floatval($baseTicketPrice + $seat->additional_charge);
                
                $bookingItem = new BookingItem();
                $bookingItem->booking_id = $booking->id;
                $bookingItem->item_type = 'ticket';
                $bookingItem->item_id = $seatId;
                $bookingItem->item_name = "Seat {$seat->row}-{$seat->number} ({$seat->type})";
                $bookingItem->quantity = 1;
                $bookingItem->unit_price = $ticketPrice;
                $bookingItem->subtotal = $ticketPrice;
                $bookingItem->save();
                
                $totalAmount += $ticketPrice;
            }
            
            // Add food items
            if ($request->has('food_items') && is_array($request->food_items)) {
                foreach ($request->food_items as $item) {
                    $foodItem = FoodItem::findOrFail($item['id']);
                    $quantity = $item['quantity'];
                    $subtotal = $foodItem->price * $quantity;
                    
                    $bookingItem = new BookingItem();
                    $bookingItem->booking_id = $booking->id;
                    $bookingItem->item_type = 'food';
                    $bookingItem->item_id = $foodItem->id;
                    $bookingItem->item_name = $foodItem->name;
                    $bookingItem->quantity = $quantity;
                    $bookingItem->unit_price = $foodItem->price;
                    $bookingItem->subtotal = $subtotal;
                    $bookingItem->save();
                    
                    $totalAmount += $subtotal;
                }
            }
            
            // Add drinks
            if ($request->has('drinks') && is_array($request->drinks)) {
                foreach ($request->drinks as $item) {
                    $drink = Drink::findOrFail($item['id']);
                    $quantity = $item['quantity'];
                    $subtotal = $drink->price * $quantity;
                    
                    $bookingItem = new BookingItem();
                    $bookingItem->booking_id = $booking->id;
                    $bookingItem->item_type = 'drink';
                    $bookingItem->item_id = $drink->id;
                    $bookingItem->item_name = $drink->name;
                    $bookingItem->quantity = $quantity;
                    $bookingItem->unit_price = $drink->price;
                    $bookingItem->subtotal = $subtotal;
                    $bookingItem->save();
                    
                    $totalAmount += $subtotal;
                }
            }
            
            // Update total amount
            $booking->total_amount = $totalAmount;
            $booking->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'booking' => $booking
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display the movie-hall assignments.
     */
    public function movieHallAssignments()
    {
        // Get all movies and halls
        $movies = Movie::all();
        $halls = Hall::all();
        
        // Get all movie-hall relationships
        $movieHalls = MovieHall::with(['movie', 'hall'])
            ->where('showtime', '>', now())
            ->orderBy('showtime')
            ->get();
        
        // Group by movie_id
        $movieHallsByMovie = $movieHalls->groupBy('movie_id');
        
        // For each movie, find which halls it's showing in
        $assignments = [];
        foreach ($movieHallsByMovie as $movieId => $showtimes) {
            $movie = $showtimes->first()->movie;
            
            // Get unique halls for this movie
            $hallsForMovie = $showtimes->pluck('hall')->unique('id')->values();
            
            // Get upcoming showtimes grouped by hall
            $upcomingShowtimes = [];
            foreach ($hallsForMovie as $hall) {
                $upcomingShowtimes[$hall->id] = $showtimes
                    ->where('hall_id', $hall->id)
                    ->sortBy('showtime')
                    ->take(3); // Just show a few upcoming showtimes
            }
            
            $assignments[] = [
                'movie' => $movie,
                'halls' => $hallsForMovie,
                'showtimes' => $upcomingShowtimes
            ];
        }
        
        return view('dashboard.movie-hall-assignments', compact('assignments', 'movies', 'halls'));
    }
    
    /**
     * Store a new movie-hall assignment with showtimes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMovieHallAssignment(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'hall_id' => 'required|exists:halls,id',
            'showtimes' => 'required|array',
            'showtimes.*' => 'required|date',
            'is_active' => 'array',
            'is_active.*' => 'nullable'
        ]);

        $createdCount = 0;
        $movie = Movie::find($request->movie_id);
        $hall = Hall::find($request->hall_id);
        
        foreach ($request->showtimes as $index => $showtime) {
            // Skip empty showtimes
            if (empty($showtime)) {
                continue;
            }
            
            // Check if showtime already exists
            $existingShowtime = MovieHall::where('movie_id', $request->movie_id)
                ->where('hall_id', $request->hall_id)
                ->where('showtime', $showtime)
                ->first();
                
            if ($existingShowtime) {
                continue; // Skip this showtime as it already exists
            }
            
            // Check if is_active is set for this index
            $isActive = isset($request->is_active) && isset($request->is_active[$index]);
            
            // Create the movie hall assignment
            MovieHall::create([
                'movie_id' => $request->movie_id,
                'hall_id' => $request->hall_id,
                'showtime' => $showtime,
                'is_active' => $isActive
            ]);
            
            $createdCount++;
        }
        
        return redirect()->route('dashboard.movieHallAssignments')
            ->with('success', "Created $createdCount showtime(s) for {$movie->title} in {$hall->name}");
    }
    
    /**
     * Display showtimes for a specific movie.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function movieShowtimes(Movie $movie)
    {
        // Get all halls
        $halls = Hall::all();
        
        // Get all movie-hall relationships for this specific movie
        $movieHalls = MovieHall::with(['movie', 'hall'])
            ->where('movie_id', $movie->id)
            ->where('showtime', '>', now())
            ->orderBy('showtime')
            ->get();
        
        // Get unique halls for this movie
        $hallsForMovie = $movieHalls->pluck('hall')->unique('id')->values();
        
        // Get upcoming showtimes grouped by hall
        $upcomingShowtimes = [];
        foreach ($hallsForMovie as $hall) {
            $upcomingShowtimes[$hall->id] = $movieHalls
                ->where('hall_id', $hall->id)
                ->sortBy('showtime');
        }
        
        $assignments = [
            [
                'movie' => $movie,
                'halls' => $hallsForMovie,
                'showtimes' => $upcomingShowtimes
            ]
        ];
        
        return view('dashboard.movie-hall-assignments', compact('assignments', 'halls'))
            ->with('highlightedMovie', $movie->id);
    }

    /**
     * Display the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function showBooking($id)
    {
        // Find booking by ID manually to avoid route model binding issues
        $booking = Booking::with([
            'movieHall.movie',
            'movieHall.hall',
            'items'
        ])->find($id);

        if (!$booking) {
            abort(404, 'Booking not found');
        }

        // Debug log to check what's being passed
        \Log::info('Showing booking', [
            'id' => $booking->id,
            'booking_number' => $booking->booking_number,
            'customer_name' => $booking->customer_name,
            'total_amount' => $booking->total_amount,
            'movieHall_exists' => $booking->movieHall ? true : false,
            'movie_title' => $booking->movieHall && $booking->movieHall->movie ? $booking->movieHall->movie->title : null,
            'items_count' => $booking->items->count()
        ]);

        return view('dashboard.bookings.show', compact('booking'));
    }

    /**
     * Print the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function printBooking($id)
    {
        $booking = Booking::with([
            'movieHall.movie',
            'movieHall.hall',
            'items'
        ])->find($id);

        if (!$booking) {
            abort(404, 'Booking not found');
        }

        return view('dashboard.bookings.print', compact('booking'));
    }

    /**
     * Confirm a pending booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmBooking(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending bookings can be confirmed.'
            ], 400);
        }

        $booking->status = 'confirmed';
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking confirmed successfully.'
        ]);
    }

    /**
     * Show the form for creating a new booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createBooking(Request $request)
    {
        $query = Movie::query();

        // Handle search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%");
        }

        // Get 6 movies per page
        $movies = $query->where('is_active', true)
                       ->orderBy('title')
                       ->paginate(6);

        return view('dashboard.bookings.create', compact('movies'));
    }

    /**
     * Show the showtime selection step.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function selectShowtime(Movie $movie)
    {
        $movieHalls = MovieHall::with('hall')
            ->where('movie_id', $movie->id)
            ->where('is_active', true)
            ->where('showtime', '>', now())
            ->orderBy('showtime')
            ->get();

        return view('dashboard.bookings.showtime', compact('movie', 'movieHalls'));
    }

    /**
     * Save a booking from the POS system.
     */
    public function saveBooking(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Create booking
            $booking = new Booking();
            $booking->booking_number = Booking::generateBookingNumber();
            $booking->movie_hall_id = $request->showtime_id;
            $booking->customer_name = $request->customer['name'];
            $booking->customer_email = $request->customer['email'];
            $booking->customer_phone = $request->customer['phone'];
            $booking->notes = $request->customer['notes'] ?? null;
            $booking->status = 'confirmed';
            $booking->total_amount = $request->total_amount;
            $booking->save();
            
            // Add seats/tickets
            foreach ($request->seats as $seatData) {
                $seat = Seat::find($seatData['seat_id']);
                $bookingItem = new BookingItem();
                $bookingItem->booking_id = $booking->id;
                $bookingItem->item_type = 'ticket';
                $bookingItem->item_id = $seatData['seat_id'];
                $bookingItem->item_name = "Seat {$seat->row}{$seat->number} ({$seat->type})";
                $bookingItem->quantity = 1;
                $bookingItem->unit_price = $seatData['price'];
                $bookingItem->subtotal = $seatData['price'];
                $bookingItem->save();
            }
            
            // Add food items
            foreach ($request->food_items as $foodItem) {
                $food = FoodItem::find($foodItem['item_id']);
                $bookingItem = new BookingItem();
                $bookingItem->booking_id = $booking->id;
                $bookingItem->item_type = 'food';
                $bookingItem->item_id = $foodItem['item_id'];
                $bookingItem->item_name = $food->name;
                $bookingItem->quantity = $foodItem['quantity'];
                $bookingItem->unit_price = $foodItem['price'];
                $bookingItem->subtotal = $foodItem['price'] * $foodItem['quantity'];
                $bookingItem->save();
            }
            
            // Add drinks
            foreach ($request->drinks as $drink) {
                $drinkItem = Drink::find($drink['item_id']);
                $bookingItem = new BookingItem();
                $bookingItem->booking_id = $booking->id;
                $bookingItem->item_type = 'drink';
                $bookingItem->item_id = $drink['item_id'];
                $bookingItem->item_name = $drinkItem->name;
                $bookingItem->quantity = $drink['quantity'];
                $bookingItem->unit_price = $drink['price'];
                $bookingItem->subtotal = $drink['price'] * $drink['quantity'];
                $bookingItem->save();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Booking saved successfully',
                'booking' => $booking->load('items')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function users()
    {
        return view('dashboard.users.index');
    }
}
