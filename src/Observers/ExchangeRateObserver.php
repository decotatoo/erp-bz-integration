<?php

namespace Decotatoo\WoocommerceIntegration\Observers;

use App\Models\ExchangeRate;
use Decotatoo\WoocommerceIntegration\Jobs\Misc\UpdateExchangeRate;

class ExchangeRateObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Exchange Rate "created" event.
     *
     * @param  ExchangeRate  $exchangeRate
     * @return void
     */
    public function created(ExchangeRate $exchangeRate)
    {
        $this->updated($exchangeRate);        
    }

    /**
     * Handle the Exchange Rate "updated" event.
     *
     * @param  ExchangeRate  $exchangeRate
     * @return void
     */
    public function updated(ExchangeRate $exchangeRate)
    {
        UpdateExchangeRate::dispatch()->afterCommit()->onQueue('high');
    }

    /**
     * Handle the Exchange Rate "deleted" event.
     *
     * @param  ExchangeRate  $exchangeRate
     * @return void
     */
    public function deleted(ExchangeRate $exchangeRate)
    {
        $this->updated($exchangeRate);
    }
}
