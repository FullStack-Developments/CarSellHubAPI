<?php

namespace Database\Seeders;

use App\Models\Ad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ads = [
            [
                'user_id' => 2,
                'full_name' => 'John Doe',
                'image' => 'images/first_car.jpg',
                'link' => 'https://example.com',
                'location' => 'New York',
                'hits' => 10,
                'views' => 100,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'is_active' => true,
                'status' => 'approved',
            ],
            [
                'user_id' => 2,
                'full_name' => 'Jane Smith',
                'image' => 'images/first_car.jpg',
                'link' => 'https://example.org',
                'location' => 'Los Angeles',
                'hits' => 5,
                'views' => 50,
                'start_date' => now(),
                'end_date' => now()->addDays(15),
                'is_active' => true,
                'status' => 'pending',
            ],
            [
                'user_id' => 3,
                'full_name' => 'Alice Johnson',
                'image' => 'images/second_car.jpg',
                'link' => 'https://anotherexample.com',
                'location' => 'Chicago',
                'hits' => 20,
                'views' => 200,
                'start_date' => now()->subDays(20),
                'end_date' => now()->subDay(),
                'is_active' => false,
                'status' => 'approved',
            ],
            [
                'user_id' => 2,
                'full_name' => 'Bob Brown',
                'image' => 'images/third_car.jpg',
                'link' => 'https://yetanotherexample.com',
                'location' => 'Miami',
                'hits' => 0,
                'views' => 0,
                'start_date' => now()->subDays(30),
                'end_date' => now()->subDays(15),
                'is_active' => false,
                'status' => 'rejected',
            ],
            [
                'user_id' => 3,
                'full_name' => 'Charlie Green',
                'image' => 'images/third_car.jpg',
                'link' => 'https://example.net',
                'location' => 'Seattle',
                'hits' => 30,
                'views' => 300,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(25),
                'is_active' => true,
                'status' => 'approved',
            ],
            [
                'user_id' => 3,
                'full_name' => 'David White',
                'image' => 'images/third_car.jpg',
                'link' => 'https://example.co',
                'location' => 'Houston',
                'hits' => 15,
                'views' => 150,
                'start_date' => now(),
                'end_date' => now()->addDays(5),
                'is_active' => true,
                'status' => 'pending',
            ],
        ];
        foreach ($ads as $ad) {
            $image = $this->storeImageToStorage($ad['image']);
            Ad::query()->create([
                'user_id' => $ad['user_id'],
                'full_name' => $ad['full_name'],
                'image' => $image,
                'link' => $ad['link'],
                'location' => $ad['location'],
                'hits' => $ad['hits'],
                'views' => $ad['views'],
                'start_date' => $ad['start_date'],
                'end_date' => $ad['end_date'],
                'is_active' => $ad['is_active'],
                'status' => $ad['status'],
            ]);
        }
    }
    private function storeImageToStorage($image): string{
        $file = new File(public_path($image));
        $fileName = Str::uuid() . '_' . time() . '.' . $file->extension();
        $filePath = Storage::disk('public')->putFileAs('advertisements', $file, $fileName);
        return URL::to('/') . '/storage/' . $filePath;
    }
}
