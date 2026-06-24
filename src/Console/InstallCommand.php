<?php

namespace Mohsin\StripeKit\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'stripe-kit:install {--force : Overwrite existing files}';
    protected $description = 'Install Stripe Kit (publish config, views, controller)';

    public function handle()
    {
        $force = $this->option('force');

        // CONFIG
        $this->publishFile(
            __DIR__ . '/../../../config/stripe-kit.php',
            config_path('stripe-kit.php'),
            $force
        );

        // VIEW
        $this->publishFile(
            __DIR__ . '/../../../resources/views/stripe.blade.php',
            resource_path('views/stripe.blade.php'),
            $force
        );

        // CONTROLLER
        $this->publishFile(
            __DIR__ . '/../../Http/Controllers/StripeController.php',
            app_path('Http/Controllers/StripeController.php'),
            $force
        );

        $this->info('Stripe Kit Installed Successfully 🚀');
    }

    private function publishFile($source, $destination, $force = false)
    {
        if (File::exists($destination) && !$force) {
            $this->warn("Skipped (already exists): " . $destination);
            return;
        }

        File::ensureDirectoryExists(dirname($destination));
        File::copy($source, $destination);

        $this->info("Published: " . $destination);
    }
}
