<?php /** @noinspection MethodShouldBeFinalInspection */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use JetBrains\PhpStorm\NoReturn;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    #[NoReturn] public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $sellerRole = Role::create(['name' => 'seller']);
        $clientRole = Role::create(['name' => 'client']);

        $permissions = [
            'add-cars', 'index-cars', 'update-cars', 'delete-cars'
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $adminRole->syncPermissions($permissions);

        $sellerRole->givePermissionTo(['add-cars', 'update-cars', 'index-cars']);
        $clientRole->givePermissionTo(['index-cars']);

        $adminUser = User::factory()->create([
            'email' => 'admin@admin.com',
            'password' => Hash::make('adminadmin'),
            'email_verified_at' => now(),
        ]);

        $adminUser->assignRole('admin');

        $adminPermission = $adminRole->permissions()->pluck('name')->toArray();
        $adminUser->givePermissionTo($adminPermission);

        $sellerUser = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => Hash::make('12345678'),
        ]);
        $sellerUser->assignRole($sellerRole);
        $sellerPermission = $sellerRole->permissions()->pluck('name')->toArray();
        $sellerUser->givePermissionTo($sellerPermission);

    }
}
