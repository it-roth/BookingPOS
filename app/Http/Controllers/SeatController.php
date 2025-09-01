<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Hall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\MovieHall;
use App\Models\BookingItem;

class SeatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Seat::query()->with('hall');
        
        // Apply hall filter
        if ($request->has('hall_filter') && $request->hall_filter) {
            $query->where('hall_id', $request->hall_filter);
        }
        
        // Apply type filter
        if ($request->has('type_filter') && $request->type_filter) {
            $query->where('type', $request->type_filter);
        }
        
        $seats = $query->latest()->paginate(20);
        
        // Maintain the filters in pagination links
        $seats->appends($request->only(['hall_filter', 'type_filter']));
        
        return view('seats.index', compact('seats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $halls = Hall::where('is_active', true)->get();
        return view('seats.create', compact('halls'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'hall_id' => 'required|exists:halls,id',
            'row' => 'required|string|max:10',
            'number' => 'required|integer|min:1',
            'type' => 'required|string|in:regular,premium,vip',
            'is_available' => 'boolean',
            'additional_charge' => 'required|numeric|min:0',
        ]);

        // Check if the seat already exists
        $existingSeat = Seat::where('hall_id', $request->hall_id)
            ->where('row', $request->row)
            ->where('number', $request->number)
            ->first();
        
        if ($existingSeat) {
            return redirect()->route('seats.create')
                ->with('error', "Seat in Row {$request->row}, Number {$request->number} already exists in this hall. Please choose a different position or edit the existing seat.")
                ->withInput();
        }

        Seat::create($request->all());

        return redirect()->route('dashboard.seats')
            ->with('success', 'Seat created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seat  $seat
     * @return \Illuminate\Http\Response
     */
    public function show(Seat $seat)
    {
        // Load the hall this seat belongs to
        $seat->load('hall');
        
        // Get upcoming showtimes for movies in this hall
        $upcomingShowtimes = [];
        $bookingHistory = [];
        
        try {
            $upcomingShowtimes = MovieHall::with('movie')
                ->where('hall_id', $seat->hall_id)
                ->where('showtime', '>', now())
                ->orderBy('showtime')
                ->take(5)
                ->get();
            
            // Get booking history for this seat if the booking tables exist
            if (Schema::hasTable('bookings') && Schema::hasTable('booking_items')) {
                $bookingHistory = BookingItem::with(['booking.movieHall.movie'])
                    ->where('item_type', 'ticket')
                    ->where('item_id', $seat->id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            }
        } catch (\Exception $e) {
            // Log the error but continue with empty collections
            Log::error('Error loading seat related data: ' . $e->getMessage());
        }
        
        return view('seats.show', compact('seat', 'upcomingShowtimes', 'bookingHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seat  $seat
     * @return \Illuminate\Http\Response
     */
    public function edit(Seat $seat)
    {
        $halls = Hall::where('is_active', true)->get();
        return view('seats.edit', compact('seat', 'halls'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seat  $seat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seat $seat)
    {
        $request->validate([
            'hall_id' => 'required|exists:halls,id',
            'row' => 'required|string|max:10',
            'number' => 'required|integer|min:1',
            'type' => 'required|string|in:regular,premium,vip',
            'is_available' => 'boolean',
            'additional_charge' => 'required|numeric|min:0',
        ]);

        // Check if the seat position is being changed
        if ($seat->hall_id != $request->hall_id || $seat->row != $request->row || $seat->number != $request->number) {
            // Check if another seat already exists at the new position
            $existingSeat = Seat::where('hall_id', $request->hall_id)
                ->where('row', $request->row)
                ->where('number', $request->number)
                ->where('id', '!=', $seat->id)
                ->first();
            
            if ($existingSeat) {
                return redirect()->route('seats.edit', $seat)
                    ->with('error', "A seat already exists at Row {$request->row}, Number {$request->number} in the selected hall.")
                    ->withInput();
            }
        }

        // Make sure is_available is set properly
        $data = $request->all();
        $data['is_available'] = $request->has('is_available');

        $seat->update($data);

        return redirect()->route('dashboard.seats')
            ->with('success', 'Seat updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seat  $seat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seat $seat)
    {
        $seat->delete();

        return redirect()->route('dashboard.seats')
            ->with('success', 'Seat deleted successfully');
    }

    /**
     * Show the form for bulk creation of seats.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkCreate(Request $request)
    {
        $halls = Hall::where('is_active', true)->get();
        
        // For debugging - add message to session to show what method was used
        session()->flash('debug', 'Request method: ' . $request->method());
        
        $rowStart = $request->input('row_start');
        $rowEnd = $request->input('row_end');
        $numberStart = $request->input('number_start');
        $numberEnd = $request->input('number_end');
        
        // Selected hall
        $hallId = $request->input('hall_id');
        $selectedHall = null;
        
        if ($hallId) {
            $selectedHall = Hall::find($hallId);
        }
        
        return view('seats.bulk-create', compact(
            'halls', 
            'rowStart', 
            'rowEnd', 
            'numberStart', 
            'numberEnd',
            'selectedHall'
        ));
    }

    /**
     * Store bulk created seats in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'hall_id' => 'required|exists:halls,id',
            'row_start' => 'required|string|max:2',
            'row_end' => 'required|string|max:2',
            'number_start' => 'required|integer|min:1',
            'number_end' => 'required|integer|min:1|gte:number_start',
            'type' => 'required|string|in:regular,premium,vip',
            'additional_charge' => 'required|numeric|min:0',
            'is_available' => 'boolean',
        ]);
        
        $rowStart = strtoupper($request->input('row_start'));
        $rowEnd = strtoupper($request->input('row_end'));
        $numberStart = (int)$request->input('number_start');
        $numberEnd = (int)$request->input('number_end');
        
        $rows = [];
        
        // Handle numeric rows
        if (is_numeric($rowStart) && is_numeric($rowEnd)) {
            for ($i = (int)$rowStart; $i <= (int)$rowEnd; $i++) {
                $rows[] = (string)$i;
            }
        } 
        // Handle alphabetic rows
        else {
            $startOrd = ord($rowStart);
            $endOrd = ord($rowEnd);
            
            if ($startOrd <= $endOrd) {
                for ($i = $startOrd; $i <= $endOrd; $i++) {
                    $rows[] = chr($i);
                }
            }
        }
        
        $createdCount = 0;
        $existingCount = 0;
        
        foreach ($rows as $row) {
            for ($number = $numberStart; $number <= $numberEnd; $number++) {
                // Check if seat already exists
                $existingSeat = Seat::where('hall_id', $request->input('hall_id'))
                    ->where('row', $row)
                    ->where('number', $number)
                    ->first();
                
                if (!$existingSeat) {
                    Seat::create([
                        'hall_id' => $request->input('hall_id'),
                        'row' => $row,
                        'number' => $number,
                        'type' => $request->input('type'),
                        'additional_charge' => $request->input('additional_charge'),
                        'is_available' => $request->has('is_available'),
                    ]);
                    $createdCount++;
                } else {
                    $existingCount++;
                }
            }
        }
        
        if ($createdCount > 0) {
            return redirect()->route('dashboard.seats')
                ->with('success', "{$createdCount} seats created successfully." . 
                    ($existingCount > 0 ? " {$existingCount} seats already existed and were skipped." : ""));
        } else {
            return redirect()->route('dashboard.seats')
                ->with('warning', "No seats were created. All {$existingCount} seats already exist.");
        }
    }
} 