<?php /** @noinspection MethodShouldBeFinalInspection */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'phone_number' => '123456789',
                'password' => bcrypt('root')
            ]);

        $adminUser->assignRole('admin');

        $adminPermission = $adminRole->permissions()->pluck('name')->toArray();
        $adminUser->givePermissionTo($adminPermission);

        //==========================================================================
        //        $sellerUser = User::factory()->create([
        //            'name' => 'Seller User',
        //            'email' => 'seller@seller.com',
        //            'password' => bcrypt('12345')
        //        ]);
        //        $sellerUser->assignRole($sellerRole);
        //        $sellerPermission = $sellerRole->permissions()->pluck('name')->toArray();
        //        $sellerUser->givePermissionTo($sellerPermission);

        //==========================================================================
        //        $clientUser = User::factory()->create([
        //            'name' => 'Client User',
        //            'email' => 'client@client.com',
        //            'password' => bcrypt('12345')
        //        ]);
        //        $clientUser->assignRole($clientRole);
        //        $clientPermission = $clientRole->permissions()->pluck('name')->toArray();
        //        $clientUser->givePermissionTo($clientPermission);
    }
}
