<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'hall_id',
        'row',
        'number',
        'type',
        'is_available',
        'additional_charge'
    ];
    
    protected $casts = [
        'is_available' => 'boolean',
        'number' => 'integer',
        'additional_charge' => 'decimal:2'
    ];
    
    /**
     * Get the hall that owns the seat.
     */
    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }
}
