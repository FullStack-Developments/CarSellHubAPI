<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id', 'username', 'phone_number',
        'is_public', 'comment', 'status',
    ];

    public function car() : BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
