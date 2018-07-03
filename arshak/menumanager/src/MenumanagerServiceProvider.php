<?php

namespace Arshak\Menumanager;

use Illuminate\Support\ServiceProvider;

class MenumanagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';

        $this->loadViewsFrom(__DIR__ . '/views', 'menumanager');
        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/menumanager'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Arshak\Menumanager\MenuController');
    }
}
