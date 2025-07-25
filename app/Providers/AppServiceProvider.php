<?php

// File: app/Providers/EventServiceProvider.php
namespace App\Providers;

use App\Listeners\AuthActivityListener;
use App\Policies\ActivityLogPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        // Event listeners lainnya...
    ];

    /**
     * The subscriber classes to register.
     */
    protected $subscribe = [
        AuthActivityListener::class,
    ];
    protected $policies = [
         'activity-log' => ActivityLogPolicy::class,
         'user' => UserPolicy::class,
    ];
    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
