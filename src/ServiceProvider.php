<?php

namespace Fomvasss\Lte3;

use Illuminate\Support\Facades\Route;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->environment('production')) {
            $this->registerRoutes();
        }

        // $this->loadTranslationsFrom(
        //     __DIR__.'/../resources/lang', 'lte'
        // );

        $this->loadViewsFrom(
            __DIR__ . '/../resources/views', 'lte3'
        );

        $this->registerPublishing();
    }

    private function registerRoutes()
    {
        Route::namespace('Fomvasss\Lte3\Http\Controllers')
            ->as('lte3.')
            ->prefix('lte3')
            ->middleware(config('lte3.middleware', []))
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
            });
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/lte3.php' => config_path('lte3.php'),
            ], 'lte3-config');

            $this->publishes([
                base_path('vendor/almasaeed2010/adminlte/dist') => public_path('vendor/adminlte/dist'),
                base_path('vendor/almasaeed2010/adminlte/plugins') => public_path('vendor/adminlte/plugins'),
            ], 'lte3-adminlte-assets');

            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/lte3'),
            ], 'lte3-assets');

            // Partial views
            foreach (['auth', 'examples', 'components', 'layouts', 'parts',] as $key) {
                $this->publishes([
                    __DIR__ . '/../resources/views/' . $key => resource_path('views/vendor/lte3/' . $key),
                ], 'lte-view-' . $key);
            }
            
            // All views
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/lte3'),
            ], 'lte3-views');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/lte3.php', 'lte3');

        $this->app->singleton(\Fomvasss\Lte3\Lte::class, function () {
            return new \Fomvasss\Lte3\Lte;
        });

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Lte3', "Fomvasss\\Lte3\\Facades\\Lte");

        $this->commands([
            Console\InstallCommand::class,
            //Console\PublishCommand::class,
        ]);
    }
}
