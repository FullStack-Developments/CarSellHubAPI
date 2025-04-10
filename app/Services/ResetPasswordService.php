<?php

namespace App\Services;

use App\Jobs\ResetPassword;
use App\Models\User;
use App\Traits\OtpTokenTrait;

class ResetPasswordService
{
    use OtpTokenTrait;
    public function forgetPassword($request) : void
    {
        $user = User::query()
            ->where('email', $request->input('email'))
            ->first();
       $otp_token = $this->generateOtpToken($request['email']);
       ResetPassword::dispatch($user, $otp_token);
    }
}
