<?php

namespace App\Listeners;

use App\Events\RegisterUserEvent;
use App\Jobs\SendEmailVerification;
use App\Notifications\EmailVerificationNotification;
use App\Traits\OtpTokenTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use JetBrains\PhpStorm\NoReturn;

class RegisterUserListener
{
    use OtpTokenTrait;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RegisterUserEvent $event): void
    {
        $otp = $this->generateOtpToken($event->user['email']);
        SendEmailVerification::dispatch($event->user, $otp);
    }
}

