<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieHall extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'hall_id',
        'showtime',
        'is_active'
    ];

    protected $casts = [
        'showtime' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Get the movie for this movie-hall pairing.
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * Get the hall for this movie-hall pairing.
     */
    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    /**
     * Get the bookings for this movie-hall pairing.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
