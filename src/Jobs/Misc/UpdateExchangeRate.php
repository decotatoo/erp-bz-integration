<?php

namespace Decotatoo\WoocommerceIntegration\Jobs\Misc;

use App\Models\ExchangeRate;
use Decotatoo\WoocommerceIntegration\Services\WordPressRestApi;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class UpdateExchangeRate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(WordPressRestApi $wp_api)
    {
        $exchange_rates = ExchangeRate::all(['from_currency', 'to_currency', 'rate']);

        try {
            $response = $wp_api->post('/exchange-rate', $exchange_rates->toArray());

            if ($response->getStatusCode() !== 200) {
                throw new Exception('Exchange rate update failed');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
