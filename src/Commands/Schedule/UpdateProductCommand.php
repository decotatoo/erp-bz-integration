<?php

namespace Decotatoo\Bz\Commands\Schedule;

use Decotatoo\Bz\Jobs\Product\CalculateStock;
use Decotatoo\Bz\Jobs\Product\Update;
use Decotatoo\Bz\Models\BzProduct;
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
    protected $signature = 'bz:schedule:update-product';

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
        $bz_products = BzProduct::all();

        $this->alert('Start update product. Total: '. $bz_products->count());

        foreach ($bz_products as $bz_product) {
            $this->info('Start update product with id: ' . $bz_product->id);

            $this->line('[bz] Updating product info');
            Update::dispatch($bz_product->product)->onQueue('high');

            $this->line('[bz] Updating product stock');
            CalculateStock::dispatch($bz_product->product)->onQueue('low');

            $this->newLine();
        }

        return 0;
    }
}