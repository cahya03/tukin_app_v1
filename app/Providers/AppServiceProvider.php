<?php

// File: app/Providers/EventServiceProvider.php
namespace App\Providers;

use App\Listeners\AuthActivityListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
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

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}

// File: app/Http/Kernel.php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     */
    protected $middleware = [
        // Middleware global lainnya...
        \App\Http\Middleware\LogHttpActivity::class, // Tambahkan ini
    ];

    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            // Web middleware lainnya...
        ],

        'api' => [
            // API middleware lainnya...
        ],
    ];

    /**
     * The application's route middleware.
     */
    protected $routeMiddleware = [
        // Route middleware lainnya...
        'log.activity' => \App\Http\Middleware\LogHttpActivity::class,
    ];
}