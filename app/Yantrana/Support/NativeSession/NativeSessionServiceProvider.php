<?php

namespace App\Yantrana\Support\NativeSession;

/*
 * Service Provider for NativeSession
 *-------------------------------------------------------- */

use Illuminate\Support\ServiceProvider;

class NativeSessionServiceProvider extends ServiceProvider
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
        // Register 'nativeSession' instance container to our NativeSession object
        $this->app['NativeSession'] = $this->app->share(function ($app) {
            $storage = $this->app['session'];

            return new NativeSession($storage);
        });

        // Register Alias
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('NativeSession', NativeSessionFacade::class);
        });
    }
}
