<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Yantrana\Components\Home\MenuEngine;

class MenuComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(MenuEngine $menuEngine)
    {	
        // Using class based composers...
        view()->composer(
            '*', \App\Http\ViewComposers\MenuComposer::class
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
