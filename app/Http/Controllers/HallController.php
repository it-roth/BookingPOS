<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use App\Models\MovieHall;
use Illuminate\Http\Request;

class HallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $halls = Hall::latest()->paginate(10);
        return view('halls.index', compact('halls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('halls.create');
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
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'hall_type' => 'required|string|max:50',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        Hall::create($request->all());

        return redirect()->route('dashboard.halls')
            ->with('success', 'Hall created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hall  $hall
     * @return \Illuminate\Http\Response
     */
    public function show(Hall $hall)
    {
        // Load seats
        $hall->load('seats');
        
        // Get all movies showing in this hall with future showtimes
        $movieHalls = MovieHall::with('movie')
            ->where('hall_id', $hall->id)
            ->where('is_active', true)
            ->where('showtime', '>', now())
            ->orderBy('showtime')
            ->get();
        
        // Group showtimes by movie
        $movieShowtimes = $movieHalls->groupBy('movie_id');
        
        return view('halls.show', compact('hall', 'movieShowtimes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hall  $hall
     * @return \Illuminate\Http\Response
     */
    public function edit(Hall $hall)
    {
        return view('halls.edit', compact('hall'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hall  $hall
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hall $hall)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'hall_type' => 'required|string|max:50',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $hall->update($request->all());

        return redirect()->route('dashboard.halls')
            ->with('success', 'Hall updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hall  $hall
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hall $hall)
    {
        // Check if hall has seats
        if ($hall->seats->count() > 0) {
            return redirect()->route('halls.index')
                ->with('error', 'Cannot delete hall with seats. Remove seats first.');
        }
        
        $hall->delete();

        return redirect()->route('dashboard.halls')
            ->with('success', 'Hall deleted successfully');
    }
} 