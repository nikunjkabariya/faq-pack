<?php

namespace Nikunjkabariya\Faq;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

//use Illuminate\Support\Facades\App;
//use Illuminate\Contracts\Routing\Registrar as Router;
//use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class FaqServiceProvider extends ServiceProvider {

    /**
     * This will be used to register configuration
     *
     * @var  string
     */
    protected $packageName = 'faq';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        // Load routes
        //$this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
        // Regiter migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Publish your config
        $this->publishes(
                [__DIR__ . '/config/faq.php' => config_path($this->packageName . '.php')], 'config'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {

        // Merge config file
        if (file_exists(base_path() . '/config/faq.php')) {
            $this->mergeConfigFrom(base_path() . '/config/faq.php', $this->packageName);
        } else {
            $this->mergeConfigFrom(__DIR__ . '/config/faq.php', $this->packageName);
        }

        // Load routes
        Route::group(['prefix' => 'api'], function ($router) {
            // By default, use the routes file provided in vendor
            $routeFilePathInUse = __DIR__ . '/routes/routes.php';

            // but if there's a file with the same name in routes/backpack, use that one
            if (file_exists(base_path() . '/config/faq.php') && file_exists(base_path() . '/routes/faq.php')) {
                $routeFilePathInUse = base_path() . '/routes/faq.php';
            }

            require $routeFilePathInUse;
        });

        $this->app->make('Nikunjkabariya\Faq\FaqController');
    }

    /**
     * Load the standard routes file for the application.
     *
     * @param  string  $path
     * @return mixed
     */
    /* protected function loadRoutesFrom() {
      Route::group([
      'middleware' => 'api',
      'namespace' => $this->namespace,
      'prefix' => 'api',
      ], function ($router) {
      require __DIR__ . '/routes.php';
      });
      } */
}
