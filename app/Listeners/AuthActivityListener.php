<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AuthActivityListener
{
    /**
     * Handle login events
     */
    public function handleLogin(Login $event): void
    {
        $name = $event->user->name ?? 'unknown';
        ActivityLogService::logLoginSuccess($name);
    }

    /**
     * Handle logout events
     */
    public function handleLogout(Logout $event): void
    {
        $name = $event->user->name ?? 'unknown';
        ActivityLogService::logLogout($name);
    }

    /**
     * Handle failed login events
     */
    public function handleFailed(Failed $event): void
    {
        $email = $event->credentials['email'] ?? 'unknown';
        ActivityLogService::logLoginFailed($email);
    }

    /**
     * Handle registration events
     */
    public function handleRegistered(Registered $event): void
    {
        $name = $event->user->name ?? 'unknown';
        ActivityLogService::logRegister($name);
    }

    /**
     * Handle email verification events
     */
    public function handleVerified(Verified $event): void
    {
        ActivityLogService::log(
            ActivityLogService::EMAIL_VERIFICATION,
            'Email berhasil diverifikasi'
        );
    }

    /**
     * Register the listeners for the subscriber
     */
    public function subscribe($events): void
    {
        $events->listen(
            Login::class,
            [AuthActivityListener::class, 'handleLogin']
        );

        $events->listen(
            Logout::class,
            [AuthActivityListener::class, 'handleLogout']
        );

        $events->listen(
            Failed::class,
            [AuthActivityListener::class, 'handleFailed']
        );

        $events->listen(
            Registered::class,
            [AuthActivityListener::class, 'handleRegistered']
        );

        $events->listen(
            Verified::class,
            [AuthActivityListener::class, 'handleVerified']
        );
    }
}