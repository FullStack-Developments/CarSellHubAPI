<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['id', 'car_id'];
    public function car() : BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function scopeIsPublic($query):void{
        $query->where('is_public', true);
    }
    public function scopeIsApproved($query):void{
        $query->where('status', 'approved');
    }
    public function scopeSelectColumns($query):void{
        $query->select('car_id','full_name','phone_number','email', 'subject', 'comment','created_at');
    }

    public function scopeWithCarInfos($query):void{
        $query->with(['car' => function ($query) {
            $query->select(['id' ,'user_id', 'brand', 'model', 'manufacture_year', 'description'])
            ->with([
                'images' => fn($query) => $query->select(['car_id', 'first_image']),
            ]);
        }]);
    }
}
