<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class AuthenticatedController extends Controller
{
    public function __construct(private readonly UserService $userService){}

    public function register(RegisterRequest $request): JsonResponse
    {
        $request->validated();
        $response = $this->userService->createUser($request);
        return $this->sendSuccess(
            $response['data'],
            $response['message'],
            201
        );
    }
}
