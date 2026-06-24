<?php

namespace Mohsin\StripeKit\Providers;

use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        $this->loadViewsFrom(
            __DIR__ . '/../../resources/views',
            'stripe-kit'
        );

        $this->publishes([
            __DIR__ . '/../../config/stripe-kit.php' =>
            config_path('stripe-kit.php'),
        ], 'stripe-kit-config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/stripe-kit.php',
            'stripe-kit'
        );
    }
}
