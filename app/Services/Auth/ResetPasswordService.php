<?php

namespace App\Services;

use App\Exceptions\SameOldPasswordException;
use App\Jobs\ResetPassword;
use App\Models\User;
use App\Traits\OtpTokenTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Psr\SimpleCache\InvalidArgumentException;

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

    /**
     * @throws SameOldPasswordException
     * @throws InvalidArgumentException
     */
    public function resetPassword($request): void
    {
        $cache = Cache::store('database');
        $email_cache = $cache->get($request->ip())[0] ?? null;
        $otp_token_cache = $cache->get($request->ip())[1] ?? null;

        if($otp_token_cache != $request->input('otp_token')){
            throw new UnauthorizedException('Invalid OTP');
        }

        $user = User::query()
            ->where('email', $email_cache)
            ->first();

        if(Hash::check($request->input('new_password'), $user->password)){
            throw new SameOldPasswordException();
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);
        $cache->forget($request->ip());
    }
}
