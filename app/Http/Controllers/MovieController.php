<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = Movie::latest()->paginate(10);
        return view('movies.index', compact('movies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('movies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'genre' => 'required|string|max:100',
                'duration' => 'required|integer|min:1',
                'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'release_date' => 'required|date',
                'is_showing' => 'nullable|boolean',
            ]);

            $data = $request->except(['poster', '_token']);
            $data['is_showing'] = $request->has('is_showing');
            $data['price'] = 0.00; // Set default price
            
            // Handle poster upload
            if ($request->hasFile('poster')) {
                $posterFile = $request->file('poster');
                $filename = time() . '_' . str_replace(' ', '_', $posterFile->getClientOriginalName());
                
                // Create the directory if it doesn't exist
                $uploadPath = public_path('images/movies');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                try {
                    $success = $posterFile->move($uploadPath, $filename);
                    if (!$success) {
                        throw new \Exception('Failed to move uploaded file');
                    }
                    $data['image'] = 'images/movies/' . $filename;
                    Log::info('Movie poster saved to: ' . $data['image']);
                } catch (\Exception $e) {
                    Log::error('Failed to upload movie poster: ' . $e->getMessage());
                    return redirect()->back()
                        ->with('error', 'Failed to upload image. Please try again.')
                        ->withInput();
                }
            }

            DB::beginTransaction();
            try {
                // Create the movie
                $movie = Movie::create($data);
                DB::commit();
                
                Log::info('Movie created successfully: ' . $movie->title);
                return redirect()->route('dashboard.movies')
                    ->with('success', 'Movie created successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to create movie: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Failed to create movie: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create movie: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        // Get all halls where this movie is showing with future showtimes
        $movieHalls = \App\Models\MovieHall::with('hall')
            ->where('movie_id', $movie->id)
            ->where('is_active', true)
            ->where('showtime', '>', now())
            ->orderBy('showtime')
            ->get();
        
        // Group showtimes by hall
        $hallShowtimes = $movieHalls->groupBy('hall_id');
        
        return view('movies.show', compact('movie', 'hallShowtimes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {
        return view('movies.edit', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genre' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'release_date' => 'required|date',
            'is_showing' => 'boolean',
        ]);

        $data = $request->except('poster');
        
        // Handle poster upload
        if ($request->hasFile('poster')) {
            // Delete old image if exists
            if ($movie->image && file_exists(public_path($movie->image))) {
                unlink(public_path($movie->image));
            }
            
            $posterFile = $request->file('poster');
            $filename = time() . '_' . $posterFile->getClientOriginalName();
            
            // Create the directory if it doesn't exist
            $uploadPath = public_path('images/movies');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Debug information
            $success = $posterFile->move($uploadPath, $filename);
            if (!$success) {
                // Log error
                Log::error('Failed to upload movie poster: ' . $posterFile->getClientOriginalName());
                return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
            }
            
            $data['image'] = '/images/movies/' . $filename;
            Log::info('Movie poster updated to: ' . $data['image']);
        }

        $movie->update($data);

        return redirect()->route('dashboard.movies')
            ->with('success', 'Movie updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return redirect()->route('dashboard.movies')
            ->with('success', 'Movie deleted successfully');
    }
} 