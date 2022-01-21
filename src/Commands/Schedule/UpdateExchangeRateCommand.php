<?php

namespace Decotatoo\Bz\Commands\Schedule;

use App\Models\ExchangeRate;
use Decotatoo\Bz\Jobs\Misc\UpdateExchangeRate;
use Illuminate\Console\Command;

/**
 * TODO:TEST
 */
class UpdateExchangeRateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bz:schedule:update-exchange-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push the latest exchange rates to WooCommerce site';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->alert('Updating exchange rate');

        UpdateExchangeRate::dispatch()->afterCommit()->onQueue('default');

        $this->info('[background] Updating exchange rate');

        return 0;
    }
}