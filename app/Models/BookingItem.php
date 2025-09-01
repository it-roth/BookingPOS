<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'item_type',
        'item_id',
        'item_name',
        'quantity',
        'unit_price',
        'subtotal'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the booking that owns the item.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the model associated with the item.
     */
    public function item()
    {
        if ($this->item_type === 'ticket') {
            return $this->belongsTo(Seat::class, 'item_id');
        } elseif ($this->item_type === 'food') {
            return $this->belongsTo(FoodItem::class, 'item_id');
        } elseif ($this->item_type === 'drink') {
            return $this->belongsTo(Drink::class, 'item_id');
        }
        
        return null;
    }
}
