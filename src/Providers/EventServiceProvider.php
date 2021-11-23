<?php

namespace Decotatoo\WoocommerceIntegration\Providers;

use App\Models\ExchangeRate;
use App\Models\Festivity;
use App\Models\ProductInCatalog as Product;
use Decotatoo\WoocommerceIntegration\Models\CommerceCategory;
use Decotatoo\WoocommerceIntegration\Observers\CommerceCategoryObserver;
use Decotatoo\WoocommerceIntegration\Observers\ExchangeRateObserver;
use Decotatoo\WoocommerceIntegration\Observers\FestivityObserver;
use Decotatoo\WoocommerceIntegration\Observers\ProductObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        CommerceCategory::observe(CommerceCategoryObserver::class);
        ExchangeRate::observe(ExchangeRateObserver::class);
        Festivity::observe(FestivityObserver::class);
        Product::observe(ProductObserver::class);
    }
}