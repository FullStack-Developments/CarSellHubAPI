<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = [
            [
                'user_id' => 2,
                'brand' => 'Toyota',
                'model' => 'Camry',
                'color' => 'Blue',
                'price' => 24000,
                'manufacture_year' => 2022,
                'country' => 'USA',
                'city' => 'Los Angeles',
                'first_image' => 'images/first_car.jpg',
                'second_image' => 'images/second_car.jpg',
                'third_image' => 'images/third_car.jpg',
                "description" => "A reliable sedan with excellent fuel efficiency and a comfortable ride.",
            ],
            [
                'user_id' => 3,
                'brand' => 'Honda',
                'model' => 'Civic',
                'color' => 'Red',
                'price' => 22000,
                'manufacture_year' => 2021,
                'country' => 'Canada',
                'city' => 'Toronto',
                'first_image' => 'images/first_car.jpg',
                'second_image' => 'images/second_car.jpg',
                'third_image' => 'images/third_car.jpg',
                "description" => "A compact car known for its sporty handling and advanced safety features."
            ],
            [
                'user_id' => 2,
                'brand' => 'Ford',
                'model' => 'Mustang',
                'color' => 'Black',
                'price' => 30000,
                'manufacture_year' => 2023,
                'country' => 'USA',
                'city' => 'New York',
                'first_image' => 'images/first_car.jpg',
                'second_image' => 'images/second_car.jpg',
                'third_image' => 'images/third_car.jpg',
                "description" => "An iconic American muscle car that offers thrilling performance and modern technology."
            ],
            [
                'user_id' => 3,
                'brand' => 'BMW',
                'model' => '3 Series',
                'color' => 'White',
                'price' => 40000,
                'manufacture_year' => 2022,
                'country' => 'Germany',
                'city' => 'Munich',
                'first_image' => 'images/first_car.jpg',
                'second_image' => 'images/second_car.jpg',
                'third_image' => 'images/third_car.jpg',
                "description" => "A luxury sedan that combines performance with a premium interior and cutting-edge tech."
            ],
            [
                'user_id' => 2,
                'brand' => 'Tesla',
                'model' => 'Model 3',
                'color' => 'Silver',
                'price' => 35000,
                'manufacture_year' => 2023,
                'country' => 'USA',
                'city' => 'San Francisco',
                'first_image' => 'images/first_car.jpg',
                'second_image' => 'images/second_car.jpg',
                'third_image' => 'images/third_car.jpg',
                "description" => "An all-electric sedan with impressive range, acceleration, and advanced autopilot features."
            ],
        ];

        foreach ($cars as $car) {
            $carModel = Car::create([
                'user_id' => $car['user_id'],
                'brand' => $car['brand'],
                'model' => $car['model'],
                'manufacture_year' => $car['manufacture_year'],
                'color' => $car['color'],
                'price' => $car['price'],
                'country' => $car['country'],
                'city' => $car['city'],
                'description' => $car['description'],
            ]);
            $carModel->refresh();
            $images = $this->storeImagesToStorage([
                $car['first_image'],
                $car['second_image'],
                $car['third_image'],
            ], $carModel->id);
            CarImage::create([
                'car_id' => $carModel['id'],
                'first_image' => $images[0],
                'second_image' => $images[1],
                'third_image' => $images[2],
            ]);
        }
    }
    private function storeImagesToStorage(array $images, $carId): array{
        $uploadURLs = [];
        foreach ($images as $image) {
            $file = new File(public_path($image));
            $fileName = Str::uuid() . '_' . time() . '.' . $file->extension();
            $images_path = "cars/{$carId}";
            $filePath = Storage::disk('public')->putFileAs($images_path, $file, $fileName);
            $uploadURLs[] = URL::to('/') . '/storage/' . $filePath;
        }
        return $uploadURLs;
    }
}
