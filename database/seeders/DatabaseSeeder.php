<?php /** @noinspection MethodShouldBeFinalInspection */

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesPermissionsSeeder::class,
            CarSeeder::class,
            AdvertisementSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
