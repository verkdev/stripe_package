<?php

namespace Mohsin\StripeKit\Providers;

use Illuminate\Support\ServiceProvider;
use Mohsin\StripeKit\Console\InstallCommand;

class StripeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Routes (PACKAGE INTERNAL)
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // Views (PACKAGE NAMESPACING)
        $this->loadViewsFrom(
            __DIR__ . '/../../resources/views',
            'stripe-kit'
        );

        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/stripe-kit.php' =>
            config_path('stripe-kit.php'),
        ], 'stripe-kit-config');

        // Register install command
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/stripe-kit.php',
            'stripe-kit'
        );
    }
}
