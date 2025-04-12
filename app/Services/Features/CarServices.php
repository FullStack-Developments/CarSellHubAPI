<?php

namespace App\Services\Features;
use App\Interfaces\CarServicesInterface;
use App\Models\Car;
use App\Models\CarImage;
use App\Traits\ManageFilesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarServices implements CarServicesInterface
{
    use ManageFilesTrait;
    public function modelQuery(): Builder
    {
        return Car::query();
    }
    public function filterCars($request) : array
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
        )->latest()
            ->get();

        if($carBuilder->count() === 0) {
            $message = 'There is no cars found.';
            $carBuilder = [];
        }else{
            $message = 'Cars indexed successfully.';
            $carBuilder = $carBuilder->paginate(10);
        }
        return ['cars' => $carBuilder, 'message' => $message];
    }
    public function storeCar($request) : array
    {
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
    public function updateCarAndImages($request, $id): array
    {
        $car = $this->modelQuery()->where('id', $id)->first();
        if(!is_null($car)){
            if (
                (Auth::user()->hasRole('seller') && Auth::id() == $car['user_id']) ||
                Auth::user()->hasRole('admin')
            ) {
                $this->updateCar($request,$id);
                $this->updateCarImage($request, $car['id']);

                $car = $this->loadCarWithImages($id);

                $message = 'Car updated successfully.';
                return ['car' => $car, 'message' => $message];
            }
            throw new UnauthorizedException('You are not authorized to update car.');
        }
        else{
            throw new NotFoundHttpException('Car not found.');
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
            'first_image' => $images[0],
            'second_image' => $images[1],
            'third_image' => $images[2],
        ]);
    }
    private function updateCar($request, $id):void {
        $car = $this->modelQuery()
            ->where('id', $id)
            ->first();
        $car->update([
            'brand' => $request['brand'] ?? $car['brand'],
            'model' => $request['model'] ?? $car['model'],
            'color' => $request['color'] ?? $car['color'],
            'manufacture_year' => $request['year'] ?? $car['manufacture_year'],
            'price' => $request['price'] ?? $car['price'],
            'country' => $request['country'] ?? $car['country'],
            'city' => $request['city']?? $car['city'],
            'is_new' => $request['is_new'] ?? $car['is_new'],
            'is_sold' => $request['is_sold'] ?? $car['is_sold'],
            'description' => $request['description'] ?? $car['description'],
        ]);
    }
    private function updateCarImage($request, $carId):void {
        $carImages = CarImage::query()
            ->where('car_id', $carId)
            ->first();
        $images = $this->deleteAndRestoreNewImages($request, $carImages);
        CarImage::query()
            ->where('car_id', $carId)
            ->update([
                'first_image' => $images[0],
                'second_image' => $images[1],
                'third_image' => $images[2],
            ]);
    }
    private function deleteAndRestoreNewImages($request, $carImages): array{
        $images_path = "cars/{$carImages['id']}";

        $images[0] = $carImages->first_image;
        $images[1] = $carImages->second_image;
        $images[2] = $carImages->third_image;

        if($request->hasFile('first_image')) {
            $this->deleteImage($carImages['first_image']);
            $first_image = $this->uploadImage([$request->file('first_image')], $images_path);
            $images[0] = $first_image[0];
        }
        if($request->hasFile('second_image')) {
            $this->deleteImage($carImages['second_image']);
            $second_image = $this->uploadImage([$request->file('second_image')], $images_path);
            $images[1] = $second_image[0];
        }
        if ($request->hasFile('third_image')) {
            $this->deleteImage($carImages['third_image']);
            $third_image = $this->uploadImage([$request->file('third_image')], $images_path);
            $images[2] = $third_image[0];
        }
        return $images;
    }
    private function loadCarWithImages($carId):Car|Builder {
        return Car::with(['images' => function ($query){
            $query->select('car_id','first_image','second_image','third_image');
        }])->where('id', $carId)->first();
    }
}
