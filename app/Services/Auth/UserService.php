<?php

namespace App\Services\Auth;

use App\Events\VerifiedEmailEvent;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ManageFilesTrait;
use App\Traits\OtpTokenTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService
{
    use ManageFilesTrait, OtpTokenTrait;

    /**
     * @param $request
     * @return array
     */
    public function createUser($request): array
    {
        if($request->hasFile('picture_profile')){
            $photo = $this->uploadImageToStorage(
                [$request->file('picture_profile')],
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
            'picture_profile' => $photo[0] ?? $request['picture_profile'],
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

        event(new VerifiedEmailEvent($user));

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
     * @param $request
     * @return array
     */
    public function loginUser($request): array{
        $identifier = filter_var(
            $request->input('email_or_username'),
            FILTER_VALIDATE_EMAIL
        ) ? 'email' : 'username';

        $user = User::query()
            ->where($identifier, $request['email_or_username'])
            ->first();

        if(is_null($user)){
            throw new NotFoundHttpException("User $identifier not found.");
        }
        else{
            if(!Auth::attempt([
                    $identifier => $request->input('email_or_username'),
                    'password' => $request->input('password')
            ])){
                throw new UnauthorizedException("User $identifier & password does not match with our record.");
            }
            else{
                $user = $this->appendRolesAndPermissions($user);
                $data['user'] = new UserResource($user);
                $data['token'] = $user->createToken('token')->plainTextToken;
                $message = 'User Logged In Successfully!';
                $code = 200;
            }
        }
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    /**
     * @return array
     */
    public function logoutUser(): array{
        $user = request()->user();
        $user->tokens()->delete();

        $message = 'User Logged Out Successfully!';
        return [
            'data' => [],
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
