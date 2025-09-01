<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'category',
        'image',
        'price',
        'size',
        'is_available',
    ];
    
    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2'
    ];
}
