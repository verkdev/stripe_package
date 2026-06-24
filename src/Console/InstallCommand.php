<?php

namespace Verkdev\StripePackage\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'stripe-package:install {--force}';
    protected $description = 'Install Stripe Package (config, views, controller)';

    public function handle()
    {
        $force = $this->option('force');

        $this->publishFile(
            __DIR__ . '/../../../config/stripe-kit.php',
            config_path('stripe-kit.php'),
            $force
        );

        $this->publishFile(
            __DIR__ . '/../../../resources/views/stripe.blade.php',
            resource_path('views/stripe.blade.php'),
            $force
        );

        $this->publishFile(
            __DIR__ . '/../../Http/Controllers/StripeController.php',
            app_path('Http/Controllers/StripeController.php'),
            $force
        );

        $this->info("Stripe Package Installed Successfully 🚀");
    }

    private function publishFile($source, $destination, $force = false)
    {
        if (!file_exists($source)) {
            $this->error("Source missing: $source");
            return;
        }

        if (File::exists($destination) && !$force) {
            $this->warn("Skipped: $destination already exists");
            return;
        }

        File::ensureDirectoryExists(dirname($destination));
        File::copy($source, $destination);

        $this->info("Published: $destination");
    }
}
