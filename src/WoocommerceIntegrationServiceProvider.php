<?php

namespace Decotatoo\WoocommerceIntegration;

use Decotatoo\WoocommerceIntegration\Commands\Schedule\UpdateExchangeRateCommand;
use Decotatoo\WoocommerceIntegration\Commands\Schedule\UpdateProductCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Decotatoo\WoocommerceIntegration\Commands\WoocommerceIntegrationCommand;
use Decotatoo\WoocommerceIntegration\Providers\EventServiceProvider;
use Decotatoo\WoocommerceIntegration\Services\WordPressRestApi;
use Illuminate\Console\Scheduling\Schedule;

class WoocommerceIntegrationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('woocommerce-integration')
            ->hasConfigFile(['woocommerce', 'woocommerce-integration'])
            ->hasRoute('web')
            ->hasViews()
            ->hasMigrations([
                'alter_many_table',
                'create_wi_bins_table',
                'create_wi_categories_table',
                'create_wi_products_table',
                'create_commerce_categories_table',
            ])
            ->hasCommands([
                UpdateExchangeRateCommand::class,
                UpdateProductCommand::class,
                WoocommerceIntegrationCommand::class,
            ]);
    }

    public function packageRegistered()
    {
        $this->app->singleton(WordPressRestApi::class, function () {
            $base_url = config('woocommerce-integration.base_url');
            $base_path = config('woocommerce-integration.rest_api.base_path');
            $auth = [
                'username' => config('woocommerce-integration.credential.username'),
                'password' => config('woocommerce-integration.credential.password'),
            ];

            return new WordPressRestApi($base_url . $base_path, $auth);
        });

        $this->app->register(EventServiceProvider::class);
    }

    public function packageBooted()
    {
        // Schedule the routine task command. Only run when using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->app->booted(function () {
                /**
                 * @var \Illuminate\Console\Scheduling\Schedule $schedule
                 */
                $schedule = $this->app->make(Schedule::class);

                $schedule->command('wi:schedule:update-exchange-rate')
                    ->dailyAt('20:00')
                    ->timezone('Asia/Jakarta');

                $schedule->command('wi:schedule:update-product')
                    ->dailyAt('23:00')
                    ->timezone('Asia/Jakarta');
            });
        }
    }
}
