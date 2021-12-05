<?php

namespace Decotatoo\Bz\Providers;

use App\Models\ExchangeRate;
use App\Models\Festivity;
use App\Models\ProductInCatalog as Product;
use Decotatoo\Bz\Models\CommerceCategory;
use Decotatoo\Bz\Models\BzCustomer;
use Decotatoo\Bz\Models\BzOrder;
use Decotatoo\Bz\Observers\CommerceCategoryObserver;
use Decotatoo\Bz\Observers\ExchangeRateObserver;
use Decotatoo\Bz\Observers\FestivityObserver;
use Decotatoo\Bz\Observers\ProductObserver;
use Decotatoo\Bz\Observers\BzCustomerObserver;
use Decotatoo\Bz\Observers\BzOrderObserver;
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

        BzCustomer::observe(BzCustomerObserver::class);
        BzOrder::observe(BzOrderObserver::class);
    }
}