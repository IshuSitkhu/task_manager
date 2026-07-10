<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\NavigationComposer;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services. Whenever Laravel loads: laout.navigation, run composer
     */
    public function boot(): void
    {
        View::composer(
            'layouts.navigation',
            NavigationComposer::class
        );
    }
}
