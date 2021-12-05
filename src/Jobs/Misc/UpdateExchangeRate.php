<?php

namespace Decotatoo\Bz\Jobs\Misc;

use App\Models\ExchangeRate;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class UpdateExchangeRate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bz_wp_api;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->bz_wp_api = App::make('bz.wordpressrestapi');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $exchange_rates = ExchangeRate::all(['from_currency', 'to_currency', 'rate']);

        try {
            $response = $this->bz_wp_api->post('/exchange-rate', $exchange_rates->toArray());

            if ($response->getStatusCode() !== 200) {
                throw new Exception('[bz] Exchange rate update failed');
            }
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }
}
