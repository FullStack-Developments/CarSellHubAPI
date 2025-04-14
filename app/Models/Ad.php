<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ad extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $hidden = ['id', 'user_id', 'created_at', 'updated_at'];
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeWithFilter($query, $request): void
    {
        $query->when(
            $request->query('name'),
            fn($query, $full_name) => $query->where('full_name', 'LIKE' ,"%$full_name%")
        )->when(
            $request->query('location'),
            fn($query, $location) => $query->where('location', 'LIKE' ,"%$location%")
        )->when(
            $request->query('min_hits'),
            fn($query, $min_hits) => $query->where('hits', '>=' ,"$min_hits")
        )->when(
            $request->query('max_hits'),
            fn($query, $max_hits) => $query->where('hits', '<=' ,"$max_hits")
        )->when(
            $request->query('min_views'),
            fn($query, $min_views) => $query->where('views', '>=' ,"$min_views")
        )->when(
            $request->query('max_views'),
            fn($query, $max_views) => $query->where('views', '<=' ,"$max_views")
        );
    }

    public function scopeWithCreator($query): void{
        $query->with([
            'user' => function($query){
                $query->select('id', 'first_name', 'last_name', 'email', 'phone_number');
            }
        ]);
    }
    public function scopeIsActive($query):void{
        $query->where('is_active', 1);
    }
    public function scopeIsApproved($query):void{
        $query->where('status', 'approved');
    }

}
