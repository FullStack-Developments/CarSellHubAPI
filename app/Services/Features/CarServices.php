<?php

namespace App\Services\Features;
use App\Interfaces\CarServicesInterface;
use App\Models\Car;
use App\Models\CarImage;
use App\Traits\ManageFilesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class CarServices implements CarServicesInterface
{
    use ManageFilesTrait;
    public function modelQuery(): Builder
    {
        return Car::query();
    }
    public function storeCar($request) : array
    {
        $this->authorizeUser();
        $car = $this->createCar($request);

        $images_path = "cars/{$car['id']}";
        $images = $this->uploadImage([
            $request->file('first_image'),
            $request->file('second_image'),
            $request->file('third_image'),
        ], $images_path);
        $this->createCarImage($car['id'], $images);
        $car = $this->loadCarWithImages($car['id']);

        return ['car' => $car, 'message' => 'Car uploaded successfully.'];
    }
    private function authorizeUser() : void {
        $user = Auth::user();

        if(!$user->hasRole('seller') && !$user->hasRole('admin')){
            throw new AccessDeniedException('You do not have permission to access this page.');
        }
    }
    private function createCar($request) : Car {
        return Car::create([
            'user_id' => Auth::id(),
            'brand' => $request['brand'],
            'model' => $request['model'],
            'manufacture_year' => $request['year'],
            'color' => $request['color'],
            'price' => $request['price'],
            'country' => $request['country'],
            'city' => $request['city'],
            'is_new' => $request['is_new'] ?? true,
            'is_sold' => $request['is_sold'] ?? false,
            'description' => $request['description'] ?? null,
        ]);

    }
    private function createCarImage($carId, $images) : void{
        CarImage::create([
            'car_id' => $carId,
            'first_image' => $images['0'],
            'second_image' => $images['1'],
            'third_image' => $images['2'],
        ]);
    }
    private function loadCarWithImages($carId):Car|Builder {
           return Car::with(['images' => function ($query){
            $query->select('car_id','first_image','second_image','third_image');
        }])->where('id', $carId)->first();
    }
    public function filterCars($request) : Builder
    {
        $carBuilder =  $this->modelQuery()
            ->when(
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

            $carBuilder->with(
            [
                'user' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'email', 'phone_number', 'address', 'picture_profile');
                },
                'images' => function ($query) {
                    $query->select('car_id', 'first_image', 'second_image', 'third_image');
                },
            ]
        )->select('id','user_id','brand','model');
        return $carBuilder;
    }
}
