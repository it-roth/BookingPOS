<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MovieHall;
use App\Models\Movie;
use App\Models\Hall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieShowtimesController extends Controller
{
    /**
     * Get showtimes for a specific movie
     *
     * @param  int  $movie
     * @return \Illuminate\Http\Response
     */
    public function getShowtimes($movie)
    {
        try {
            // Validate movie exists
            $movie = Movie::find($movie);
            if (!$movie) {
                return response()->json(['error' => 'Movie not found'], 404);
            }

            // Get all the movie halls without joining - safer option
            $movieHalls = MovieHall::where('movie_id', $movie->id)
                ->orderBy('showtime')
                ->get();
            
            // Process the data to include halls manually
            $showtimes = [];
            foreach ($movieHalls as $mh) {
                $hall = Hall::find($mh->hall_id);
                
                if ($hall) {
                    $showtimes[] = [
                        'id' => $mh->id,
                        'showtime' => $mh->showtime,
                        'is_active' => $mh->is_active ? true : false,
                        'hall_name' => $hall->name,
                        'hall_type' => $hall->hall_type
                    ];
                }
            }

            return response()->json([
                'movie' => [
                    'id' => $movie->id,
                    'title' => $movie->title
                ],
                'showtimes' => $showtimes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve showtimes',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 