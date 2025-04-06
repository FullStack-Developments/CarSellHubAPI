<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'brand', 'model',
        'year', 'color', 'price',
        'location', 'is_sold', 'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany{
        return $this->hasMany(Review::class);
    }

    public function carImage() : HasMany
    {
        return $this->hasMany(CarImage::class);
    }
}
