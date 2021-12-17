<?php

namespace Decotatoo\Bz;

use Decotatoo\Bz\Commands\BzCommand;
use Decotatoo\Bz\Commands\Schedule\UpdateExchangeRateCommand;
use Decotatoo\Bz\Commands\Schedule\UpdateProductCommand;
use Decotatoo\Bz\Commands\Task\PushProductCommand;
use Decotatoo\Bz\Providers\EventServiceProvider;
use Decotatoo\Bz\Services\WooCommerceApi\WooCommerceApi;
use Decotatoo\Bz\Services\WordPressRestApi;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class BzServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('bz')
            ->hasConfigFile([
                'bz', // b2b business
            ])
            ->hasRoutes([
                'api',
                'web',
            ])
            ->hasViews()
            ->hasMigrations([
                'create_commerce_categories_table',
                'create_commerce_catalogs_table',
                'create_bz_categories_table',
                'create_unit_boxes_table',
                'create_bins_table',
                'alter_many_table',
                'create_bz_products_table',
                'create_bz_customers_table',
                'create_bz_orders_table',
                'create_bz_order_items_table',
                'create_packing_simlulations_table',
            ])
            ->hasCommands([
                BzCommand::class,
                UpdateExchangeRateCommand::class,
                UpdateProductCommand::class,
                PushProductCommand::class,
            ]);
    }

    public function packageRegistered()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton('bz.wordpress', function () {
            $base_url = config('bz.base_url');
            $base_path = config('bz.rest_api.base_path');
            $auth = [
                'username' => config('bz.rest_api.credential.username'),
                'password' => config('bz.rest_api.credential.password'),
            ];

            return new WordPressRestApi($base_url . $base_path, $auth);
        });

        $this->app->singleton('bz.woocommerce', function () {
            $base_url = config('bz.base_url');
            $consumer_key = config('bz.woocommerce.consumer_key');
            $consumer_secret = config('bz.woocommerce.consumer_secret');

            $options = [
                'version' => 'wc/' . config('bz.woocommerce.api_version'),
                'wp_api' => config('bz.woocommerce.wp_api_integration'),
                'verify_ssl' => config('bz.woocommerce.verify_ssl'),
                'query_string_auth' => config('bz.woocommerce.query_string_auth'),
                'timeout' => config('bz.woocommerce.timeout'),
                'header_total' => config('bz.woocommerce.header_total') ?? 'X-WP-Total',
                'header_total_pages' => config('bz.woocommerce.header_total_pages') ?? 'X-WP-TotalPages',
            ];

            return new WooCommerceApi($base_url, $consumer_key, $consumer_secret, $options);
        });
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

                $schedule->command('bz:schedule:update-exchange-rate')
                    ->dailyAt('20:00')
                    ->timezone('Asia/Jakarta');

                $schedule->command('bz:schedule:update-product')
                    ->dailyAt('23:00')
                    ->timezone('Asia/Jakarta');
            });
        }
    }
}
