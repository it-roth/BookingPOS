<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'capacity',
        'hall_type',
        'is_active',
        'description'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer'
    ];
    
    /**
     * Get the seats for the hall.
     */
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
    
    /**
     * Get the movie-hall pairings for this hall.
     */
    public function movieHalls()
    {
        return $this->hasMany(MovieHall::class);
    }
    
    /**
     * Get the movies shown in this hall through movie-hall pairings.
     */
    public function movies()
    {
        return $this->hasManyThrough(Movie::class, MovieHall::class, 'hall_id', 'id', 'id', 'movie_id');
    }
}
