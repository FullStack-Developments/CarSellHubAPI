<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ManageFilesTrait;
use Spatie\Permission\Models\Role;

class UserService
{
    use ManageFilesTrait;
    /**
     * @param $request
     * @return array
     */
    public function createUser($request): array
    {
        if($request->hasFile('picture_profile')){
            $photo = $this->uploadFile(
                $request->file('picture_profile'),
                'picture_profile'
            );
        }
        $userName = User::generateUserName($request['first_name'], $request['last_name']);

        $user = User::query()->create([
            'username' => $userName,
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'phone_number' => $request['phone_number'],
            'password' => bcrypt($request['password']),
            'picture_profile' => $photo ?? $request['picture_profile'],
            'address' => $request['address'],
        ]);

        $sellerRole = Role::query()->where('name', 'seller')->first();

        assert($user instanceof User);
        $user->assignRole($sellerRole);

        assert($sellerRole instanceof Role);
        $sellerPermission = $sellerRole->permissions()->pluck('name')->toArray();
        $user->givePermissionTo($sellerPermission);

        $user->load('roles', 'permissions');

        $user = User::query()->find($user['id']);
        $user = $this->appendRolesAndPermissions($user);

        $data = [];
        $data['user'] = $user;
        $data['token'] = $user->createToken('token')->plainTextToken;

        $message = 'User Registration Successfully!';

        return [
            'data' => $data,
            'message' => $message,
        ];
    }

    /**
     * @param $user
     * @return mixed
     */
    private function appendRolesAndPermissions($user): mixed
    {
        $roles = [];
        foreach ($user->roles as $role) {
            $roles[] = $role->name;
        }
        unset($user['roles']);
        $user['roles'] = $roles;

        $permissions = [];
        foreach ($user->permissions as $permission) {
            $permissions[] = $permission->name;
        }
        unset($user['permissions']);
        $user['permissions'] = $permissions;

        return $user;
    }
}
