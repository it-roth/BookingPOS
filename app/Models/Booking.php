<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'movie_hall_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the movie hall for this booking.
     */
    public function movieHall()
    {
        return $this->belongsTo(MovieHall::class);
    }

    /**
     * Get the movie associated with this booking.
     */
    public function movie()
    {
        return $this->hasOneThrough(Movie::class, MovieHall::class, 'id', 'id', 'movie_hall_id', 'movie_id');
    }

    /**
     * Get the hall associated with this booking.
     */
    public function hall()
    {
        return $this->hasOneThrough(Hall::class, MovieHall::class, 'id', 'id', 'movie_hall_id', 'hall_id');
    }

    /**
     * Get the booking items for this booking.
     */
    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    /**
     * Generate a unique booking number.
     */
    public static function generateBookingNumber()
    {
        $prefix = 'BKG-';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3));
        $number = rand(1000, 9999);
        
        return $prefix . $date . '-' . $random . $number;
    }
}
