<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Movie extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'genre',
        'duration',
        'image',
        'release_date',
        'is_showing',
        'price'
    ];
    
    // Cast attributes to appropriate types
    protected $casts = [
        'release_date' => 'date',
        'is_showing' => 'boolean',
        'duration' => 'integer',
        'price' => 'decimal:2'
    ];

    protected $attributes = [
        'is_showing' => false,
        'description' => null,
        'image' => null,
        'price' => 0.00
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($movie) {
            if (!isset($movie->price)) {
                $movie->price = 0.00;
            }
            Log::info('Creating movie: ' . $movie->title);
        });

        static::created(function ($movie) {
            Log::info('Movie created: ' . $movie->title);
        });

        static::updating(function ($movie) {
            Log::info('Updating movie: ' . $movie->title);
        });

        static::updated(function ($movie) {
            Log::info('Movie updated: ' . $movie->title);
        });

        static::deleting(function ($movie) {
            Log::info('Deleting movie: ' . $movie->title);
            // Delete associated image if exists
            if ($movie->image && file_exists(public_path($movie->image))) {
                unlink(public_path($movie->image));
            }
        });
    }
    
    /**
     * Get the movie-hall pairings for this movie.
     */
    public function movieHalls()
    {
        return $this->hasMany(MovieHall::class);
    }
    
    /**
     * Get the halls where this movie is shown through movie-hall pairings.
     */
    public function halls()
    {
        return $this->hasManyThrough(Hall::class, MovieHall::class, 'movie_id', 'id', 'id', 'hall_id');
    }
}
