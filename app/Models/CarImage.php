<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'first_image',
        'second_image',
        'third_image',
    ];

    public function car() : BelongsTo{
        return $this->belongsTo(Car::class, 'car_id');
    }
}
