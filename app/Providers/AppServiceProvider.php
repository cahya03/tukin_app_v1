<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Header;
use App\Policies\HeaderPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
    Header::class => HeaderPolicy::class,
];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
