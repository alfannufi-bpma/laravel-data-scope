<?php

namespace Bpma\DataScope;

use Illuminate\Support\ServiceProvider;

class ScopeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/data-scope.php', 'data-scope');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Publish Config
            $this->publishes([
                __DIR__.'/../config/data-scope.php' => config_path('data-scope.php'),
            ], 'data-scope-config');

            // Publish Migrations
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'data-scope-migrations');
        }
    }
}