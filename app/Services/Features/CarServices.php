<?php

namespace App\Services;
use App\Models\Car;
use App\Models\CarImage;
use App\Traits\ManageFilesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class CarServices
{
    use ManageFilesTrait;
    public function uploadCar($request) : array
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
    private function createCar($request) : Car{
        return Car::create([
            'user_id' => Auth::id(),
            'brand' => $request['brand'],
            'model' => $request['model'],
            'manufacture_year' => $request['manufacture_year'],
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
}
