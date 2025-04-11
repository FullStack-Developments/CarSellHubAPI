<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\SameOldPasswordException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\ResetPasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\ResponseTrait;
use Psr\SimpleCache\InvalidArgumentException;

class ResetPasswordController extends Controller
{
    use ResponseTrait;
    public function __construct(private readonly ResetPasswordService $passwordService) {}
    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse
    {
        $this->passwordService->forgetPassword($request);
        return $this->sendSuccess([], 'sent code to your email for reset password');
    }

    /**
     * @throws SameOldPasswordException
     * @throws InvalidArgumentException
     */
    public function resetPassword(ResetPasswordRequest $request) : JsonResponse{
        $this->passwordService->resetPassword($request);
        return $this->sendSuccess([],'password reset successfully');
    }
}
