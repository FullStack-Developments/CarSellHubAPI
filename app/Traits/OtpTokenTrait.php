<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait OtpTokenTrait
{
    public function generateOtpToken($email, $validate_token = 10): int
    {
        $otp = mt_rand(100000, 999999);
        $cache = Cache::store('database');
        $cache->put(request()->ip(), [$email, $otp], now()->addMinutes($validate_token));
        return $otp;
    }
}
