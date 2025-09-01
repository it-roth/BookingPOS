<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'category',
        'image',
        'price',
        'is_available',
        'preparation_time'
    ];
    
    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
        'preparation_time' => 'integer'
    ];
}
