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
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'larablog');

        // use this if your package has routes
        $this->setupRoutes($this->app->router);

        // load our migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // publish config if necessary
         $this->publishes([
                 __DIR__.'/config/config.php' => config_path('blog.php')
         ], 'config');

         $this->publishes([
             __DIR__ . '/resources/views' => base_path('resources/views/vendor/escuccim'),
         ], 'views');

        $this->publishes([
            __DIR__ . '/resources/lang' => base_path('resources/lang/vendor/larablog'),
        ], 'lang');


         // use the default configuration file as fallback
         $this->mergeConfigFrom(
             __DIR__.'/config/config.php', 'blog'
         );
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

        // specify the config file
         config([
                 'config/blog.php',
         ]);
    }
    private function registerClass()
    {
        $this->app->bind('escuccim',function($app){
            return new BlogClass($app);
        });
    }
}