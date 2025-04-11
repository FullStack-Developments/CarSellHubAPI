<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\ResendEmailRequest;
use App\Services\EmailVerificationService;
use Illuminate\Http\JsonResponse;
use Psr\SimpleCache\InvalidArgumentException;

class EmailVerificationController extends Controller
{
    public function __construct(private readonly EmailVerificationService $verificationService) {}

    /**
     * @throws InvalidArgumentException
     */
    public function verifyEmail(EmailVerificationRequest $request): JsonResponse
    {
        $request->validated();
        $this->verificationService->verify($request);

        return $this->sendSuccess([],'Email verified successfully');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function resendVerificationEmail(ResendEmailRequest $request): JsonResponse{
        $this->verificationService->resend($request);
        return $this->sendSuccess([], 'Verification otp code resent');
    }

}
