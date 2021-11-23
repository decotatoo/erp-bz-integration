<?php

namespace Decotatoo\WoocommerceIntegration\Commands\Schedule;

use Decotatoo\WoocommerceIntegration\Jobs\Product\CalculateStock;
use Decotatoo\WoocommerceIntegration\Jobs\Product\Update;
use Decotatoo\WoocommerceIntegration\Models\WiProduct;
use Illuminate\Console\Command;

/**
 * TODO:TEST
 */
class UpdateProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wi:schedule:update-product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push product information and stock to woocommerce site';

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
        $wi_products = WiProduct::where('wp_post_status', 'publish')->get();

        $this->alert('Start update product. Total: '. $wi_products->count());

        foreach ($wi_products as $wi_product) {
            $this->info('Start update product with id: ' . $wi_product->id);

            $this->line('[background] Updating product info');
            Update::dispatch($wi_product->product)->onQueue('high');

            $this->line('[background] Updating product stock');
            CalculateStock::dispatch($wi_product->product)->onQueue('low');

            $this->newLine();
        }

        return 0;
    }
}