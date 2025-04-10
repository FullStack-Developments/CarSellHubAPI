<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Services\ResetPasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\ResponseTrait;

class ResetPasswordController extends Controller
{
    use ResponseTrait;
    public function __construct(private readonly ResetPasswordService $passwordService) {}

    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse
    {
        $this->passwordService->forgetPassword($request);
        return $this->sendSuccess([], 'sent code to your email for reset password');
    }
}
