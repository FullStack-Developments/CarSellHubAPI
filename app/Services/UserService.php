<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ManageFilesTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'password' => Hash::make($request['password']),
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

    public function loginUser($request): array{
        $identifier = filter_var(
            $request->input('email_or_username'),
            FILTER_VALIDATE_EMAIL
        ) ? 'email' : 'username';

        $user = User::query()
            ->where($identifier, $request->input('email_or_username'))
            ->first();

        if(!is_null($user)){
            if(!Auth::attempt($request->only($identifier, 'password'))){
                $data = [];
                $message = "User $identifier & password does not match with our record.";
                $code = 401;
            }
            else{
                $user = $this->appendRolesAndPermissions($user);
                $data['user'] = $user;
                $data['token'] = $user->createToken('token')->plainTextToken;
                $message = 'User Login Successfully!';
                $code = 200;
            }
        }else{
            $data = [];
            $message = "User $identifier not found.";
            $code = 404;
        }
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
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
