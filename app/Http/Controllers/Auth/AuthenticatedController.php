<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Services\Auth\UserService;
use Illuminate\Http\JsonResponse;

class AuthenticatedController extends Controller
{
    public function __construct(private readonly UserService $userService){}

    public function register(StoreUserRequest $request): JsonResponse
    {
        $request->validated();
        $response = $this->userService->createUser($request);
        return $this->sendSuccess($response['data'], $response['message'], 201);
    }

    public function login(LoginUserRequest $request): JsonResponse{
        $request->validated();
        $response = $this->userService->loginUser($request);
        return $this->sendSuccess($response['data'], $response['message']);
    }

    public function logout(): JsonResponse{
        $response = $this->userService->logoutUser();
        return $this->sendSuccess($response['data'], $response['message']);
    }

}
