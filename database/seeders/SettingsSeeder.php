<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $setting = [[
            'website_name' => 'Car Sell Hub',
            'website_icon' => 'images/settings/car-icon1.jpg',
            'website_logo' => 'images/settings/car-logo1.jpg',
            'facebook_url' => 'https://facebook.com/carSellHub',
            'twitter_url' => 'https://twitter.com/carSellHub',
            'linkedin_url' => 'https://linkedin.com/company/carSellHub',
            'instagram_url' => 'https://instagram.com/carSellHub',
            'whatsapp_url' => 'https://wa.me/1234567890',
            'contact_email' => 'info@carsellhub.com',
            'contact_phone' => '+1234567890',
            'intro_images' => [
                'images/settings/intro-1.jpg',
                'images/settings/intro-2.jpg',
                'images/settings/intro-3.webp',
                'images/settings/intro-4.webp'
            ],
            'intro_keywords' => 'cars, sell, buy, dealership',
            'site_description' => 'Welcome to Car Sell Hub, the best place to buy and sell cars online.',
        ]];

        $image_icon = $this->storeImagesToStorage($setting[0]['website_icon'],'icons' );
        $image_logo = $this->storeImagesToStorage($setting[0]['website_logo'],'logo' );
        $intros = [];
        foreach($setting[0]['intro_images'] as $intro){
          $intros[] = $this->storeImagesToStorage($intro,'intro_images');
        }

        Setting::query()->create([
            'website_name' =>$setting[0]['website_name'],
            'website_icon' => $image_icon,
            'website_logo' => $image_logo,
            'facebook_url' => $setting[0]['facebook_url'],
            'twitter_url' => $setting[0]['twitter_url'],
            'linkedin_url' => $setting[0]['linkedin_url'],
            'instagram_url' => $setting[0]['instagram_url'],
            'whatsapp_url' => $setting[0]['whatsapp_url'],
            'contact_email' => $setting[0]['contact_email'],
            'contact_phone' => $setting[0]['contact_phone'],
            'intro_images' => json_encode($intros),
            'intro_keywords' => json_encode($setting[0]['intro_keywords']),
            'site_description' => $setting[0]['site_description'],
        ]);
    }
    private function storeImagesToStorage($image, $path): string{
        $file = new File(public_path($image));
        $fileName = Str::uuid() . '_' . time() . '.' . $file->extension();
        $images_path = "settings/{$path}";
        $filePath = Storage::disk('public')->putFileAs($images_path, $file, $fileName);
        return URL::to('/') . '/storage/' . $filePath;
    }
}
