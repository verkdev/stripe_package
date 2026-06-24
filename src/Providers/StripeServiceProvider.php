<?php

namespace Verkdev\StripePackage\Providers;

use Illuminate\Support\ServiceProvider;
use Verkdev\StripePackage\Console\InstallCommand;

class StripeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/stripe-kit.php',
            'stripe-kit'
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        $this->loadViewsFrom(
            __DIR__ . '/../../resources/views',
            'stripe-kit'
        );

        $this->publishes([
            __DIR__ . '/../../config/stripe-kit.php' => config_path('stripe-kit.php'),
        ], 'stripe-kit-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}
