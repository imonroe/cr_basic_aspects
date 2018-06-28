<?php

namespace imonroe\cr_basic_aspects;

use Illuminate\Support\ServiceProvider;

class cr_basic_aspectsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
      // Migrations:
      $this->loadMigrationsFrom(__DIR__.'/migrations');

      // Views:
      //$this->loadViewsFrom(__DIR__.'/../reources/views', 'cr_basic_aspects');
      //$this->publishes([
      //	__DIR__.'/../reources/views' => resource_path('views/imonroe/cr_basic_aspects'),
      //]);

      // Routes:
      $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}