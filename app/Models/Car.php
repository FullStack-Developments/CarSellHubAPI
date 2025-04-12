<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $array)
 */
class Car extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = ['id', 'user_id'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany{
        return $this->hasMany(Review::class);
    }

    public function images() : HasMany
    {
        return $this->hasMany(CarImage::class);
    }

    public static function scopeWithUsersAndImages($query): void{
        $query->with([
            'user' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'email', 'phone_number', 'address', 'picture_profile');
            },
            'images' => function ($query) {
                $query->select('car_id', 'first_image', 'second_image', 'third_image');
            },
        ]);
    }

    public function scopeWithImages($query): void{
        $query->with([
            'images' => function ($query){
            $query->select('car_id','first_image','second_image','third_image');
        }]);
    }

    public function scopeFilter($query, $request): void{
        $query->when(
                $request->query('brand'),
                fn($query, $brand) => $query->where('brand', $brand)
            )->when(
                $request->query('model'),
                fn ($query, $model) => $query->where('model', $model)
            )->when(
                $request->query('color'),
                fn ($query, $color) => $query->where('color', $color)
            )->when(
                $request->query('year'),
                fn ($query, $year) => $query->where('manufacture_year', $year)
            )->when(
                $request->query('min_price'),
                fn ($query, $min_price) => $query->where('price', '>=', $min_price)
            )->when(
                $request->query('max_price'),
                fn ($query, $max_price) => $query->where('price', '<=', $max_price)
            )->when(
                $request->query('country'),
                fn ($query, $country) => $query->where('country', $country)
            )->when(
                $request->query('city'),
                fn ($query, $city) => $query->where('city', $city)
            )->when(
                $request->query('is_new'),
                fn ($query, $is_new) => $query->where('is_new', $is_new)
            )->when(
                $request->query('is_sold'),
                fn ($query, $is_sold) => $query->where('is_sold', $is_sold)
            );
    }
}
