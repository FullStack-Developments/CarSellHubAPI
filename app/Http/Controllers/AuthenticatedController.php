<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundUserException;
use App\Exceptions\UnauthorizedUserException;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class AuthenticatedController extends Controller
{
    public function __construct(private readonly UserService $userService){}

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $request->validated();
        $response = $this->userService->createUser($request);
        return $this->sendSuccess($response['data'], $response['message'], 201);
    }

    /**
     * @throws UnauthorizedUserException
     * @throws NotFoundUserException
     */
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
