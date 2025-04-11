<?php

namespace App\Services;

use App\Events\VerifiedEmailEvent;
use App\Models\User;
use App\Traits\OtpTokenTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\UnauthorizedException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class EmailVerificationService
{
    use OtpTokenTrait;
    /**
     * @throws InvalidArgumentException
     */
    public function verify($request): void
    {
        $cache = Cache::store('database');
        $email_cache = $cache->get($request->ip())[0] ?? null;
        $otp_token_cache = $cache->get($request->ip())[1] ?? null;

        if($otp_token_cache != $request->otp_token){
            throw new UnauthorizedException('Invalid OTP');
        }
        User::query()
            ->where('email', $email_cache)
            ->update([
                'email_verified_at' => now(),
            ]);
        $cache->forget($request->ip());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function resend($request): void
    {
        $user = Auth::user();
        if($user['email_verified_at'] != null){
            throw new BadRequestException('Email is already verified');
        }

        $cache = Cache::store('database');
        $email_cache = $cache->get(request()->ip())[0] ?? null;

        if(is_null($email_cache) && is_null($request['email'])){
            throw new BadRequestException('your otp is expired, please enter your email to resend code again.');
        }
        if($user['email'] != $request['email']){
            throw new BadRequestException('your email is Incorrect');
        }
        $user = User::query()->where('email', $request['email'])->first();
        assert($user instanceof User);
        event(new VerifiedEmailEvent($user));

    }
}
