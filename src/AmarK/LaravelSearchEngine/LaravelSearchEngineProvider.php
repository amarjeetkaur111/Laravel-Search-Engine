<?php

namespace AmarK\LaravelSearchEngine;

use Illuminate\Support\ServiceProvider;

class LaravelSearchEngineProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/laravelSearchEngine.php' => config_path('laravelSearchEngine.php'),
        ]);
    }

    public function register()
    {
        $this->app->bind('laravelSearchEngine', function () {

            return new LaravelSearchEngine(config('laravelSearchEngine.engineId'),
                config('laravelSearchEngine.apiKey'));
        });
    }
}
