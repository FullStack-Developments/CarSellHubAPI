<?php

namespace App\Services\Features;
use App\Http\Resources\CarResource;
use App\Interfaces\CarServicesInterface;
use App\Models\Car;
use App\Models\CarImage;
use App\Traits\ManageFilesTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function PHPUnit\Framework\isEmpty;

class CarService implements CarServicesInterface
{
    use ManageFilesTrait;
    public function modelQuery(): Builder
    {
        return Car::query();
    }
    public function filterCars($request) : array
    {
        $carBuilder = $this->modelQuery()->filter($request);

        $carBuilder->withUsersAndImages()
            ->latest()
            ->get();

        if($carBuilder->count() == 0) {
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
        $images = $this->uploadImageToStorage([
            $request->file('first_image'),
            $request->file('second_image'),
            $request->file('third_image'),
        ], $images_path);

        $this->createCarImage($car['id'], $images);
        $car = $this->modelQuery()
            ->withImages()
            ->where('id', $car['id'])
            ->first();

        return ['car' => $car, 'message' => 'Car uploaded successfully.'];
    }
    /**
     * @throws AuthorizationException
     */
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

                $car = $this->modelQuery()
                    ->withImages()
                    ->where('id', $id)
                    ->first();

                $message = 'Car updated successfully.';
                return ['car' => $car, 'message' => $message];
            }
            throw new AuthorizationException('You are not authorized to update car.');
        }
        else{
            throw new NotFoundHttpException('Car not found.');
        }
    }
    /**
     * @throws AuthorizationException
     */
    public function deleteCarAndImages($id): void{
        $car = $this->modelQuery()->where('id', $id)->first();
        if(!is_null($car)) {
            if (Auth::user()->hasRole('admin')){
                $carImages = CarImage::query()->where('car_id', $id)->first();

                $this->deleteImageFromStorage([
                    $carImages['first_image'],
                    $carImages['second_image'],
                    $carImages['third_image']
                ]);
                $car->delete();
            }
            else{
                throw new AuthorizationException();
            }
        }
        else{
            throw new NotFoundHttpException('Car not found.');
        }
    }
    public function getCarById(int $id): array {
        $car = $this->modelQuery()->where('id', $id)->first();
        if(!is_null($car)){
            $car = $this->modelQuery()->withUsersAndImages()->where('id', $id)->first();
             $message = 'Car for id (' . $id .') indexed successfully.';
             return ['car' => $car, 'message' => $message];
        }
        else{
            throw new NotFoundHttpException('Car not found.');
        }
    }
    public function getCarBrands(): array
    {
        $car_brands = $this->modelQuery()
            ->distinct()
            ->select('brand')
            ->pluck('brand');
        if($car_brands->isNotEmpty()){
            $message = 'Car brands indexed successfully.';
            return ['car_brands' => $car_brands, 'message' => $message];
        }
        else{
            throw new NotFoundHttpException('There is no car brands available at the moment.');
        }
    }
    public function getCarsBySellerName(string $sellerName): array{
        $cars = $this->modelQuery()
            ->bySellerName($sellerName)
            ->paginate(10);
        if($cars->isNotEmpty()){
            $message = 'Cars indexed by seller name successfully.';
            return ['cars' => $cars, 'message' => $message];
        }else{
            throw new NotFoundHttpException('There is no cars for seller name at the moment.');
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
            $this->deleteImageFromStorage([$carImages['first_image']]);
            $first_image = $this->uploadImageToStorage([$request->file('first_image')], $images_path);
            $images[0] = $first_image[0];
        }
        if($request->hasFile('second_image')) {
            $this->deleteImageFromStorage([$carImages['second_image']]);
            $second_image = $this->uploadImageToStorage([$request->file('second_image')], $images_path);
            $images[1] = $second_image[0];
        }
        if ($request->hasFile('third_image')) {
            $this->deleteImageFromStorage([$carImages['third_image']]);
            $third_image = $this->uploadImageToStorage([$request->file('third_image')], $images_path);
            $images[2] = $third_image[0];
        }
        return $images;
    }

}
