<?php

namespace App\Providers;

use App\Listeners\MergeSessionCartOnLogin;
use App\Services\CartService;
use Illuminate\Auth\Events\Login;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartService::class, fn () => new CartService());
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Event::listen(Login::class, MergeSessionCartOnLogin::class);
    }
}