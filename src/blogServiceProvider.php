<?php

namespace Escuccim\LaraBlog;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class blogServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // use this if your package has views
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'escuccim');

        // use this if your package has lang files
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'escuccim');

        // use this if your package has routes
        $this->setupRoutes($this->app->router);

        // load our migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // use this if your package needs a config file
        // $this->publishes([
        //         __DIR__.'/config/config.php' => config_path('skeleton.php'),
        // ]);

        // use the vendor configuration file as fallback
        // $this->mergeConfigFrom(
        //     __DIR__.'/config/config.php', 'skeleton'
        // );
    }
    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Escuccim\LaraBlog\Http\Controllers'], function($router)
        {
            require __DIR__.'/Http/routes.php';
        });
    }
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerClass();

        // use this if your package has a config file
        // config([
        //         'config/skeleton.php',
        // ]);
    }
    private function registerClass()
    {
        $this->app->bind('escuccim',function($app){
            return new BlogClass($app);
        });
    }
}