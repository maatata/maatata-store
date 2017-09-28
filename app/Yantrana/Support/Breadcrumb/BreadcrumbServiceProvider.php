<?php

namespace App\Yantrana\Support\Breadcrumb;

/*
 * Service Provider for Security - 03 AUG 2015
 *-------------------------------------------------------- */

use Illuminate\Support\ServiceProvider;

class BreadcrumbServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     */
    public function register()
    {

        // Register 'breadcrumb' instance container to our Breadcrumb object
        $this->app['breadcrumb'] = $this->app->share(function ($app) {
            return new \App\Yantrana\Support\Breadcrumb\Breadcrumb();
        });

        // Register Alias
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Breadcrumb',
                \App\Yantrana\Support\Breadcrumb\BreadcrumbFacade::class);
        });
    }
}
