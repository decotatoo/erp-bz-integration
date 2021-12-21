<?php

namespace Decotatoo\Bz\Commands\Task;

use App\Models\ProductInCatalog;
use Decotatoo\Bz\Jobs\Product\Create;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class PushProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bz:task:push-product 
                            {--dry-run : Run the command in dry-run mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push new product information and stock to woocommerce site';

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
        $products = ProductInCatalog::query()
            ->doesntHave('bzProduct')
            ->where('category', 'catalog')
            ->where('customer_id', null)
            ->where('season', '!=', 'None')
            ->where('season', '!=', 'Personalize')
            ->whereHas('productCategory', function (Builder $query) {
                $query->where('category', 'NOT LIKE', '%PERSONALIZE%');
            })
            ->where(function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->where('season', 'Four Season')
                        ->orWhereHas('commerceCatalog', function (Builder $query) {
                            $query->where('is_published', true);
                        });
                })
                    ->where(function (Builder $query) {
                        $query->whereDoesntHave('festivity')
                            ->orWhereHas('festivity', function (Builder $query) {
                                $query->where('status', 'Yes');
                            });
                    });
            })
            ->get();

        $this->alert(sprintf('Found %d product(s) to push', $products->count()));

        foreach ($products as $product) {
            $this->line(sprintf('[bz] Pushing product: %s (%s)', $product->prod_id, $product->prod_name));

            if (!$this->option('dry-run')) {
                Create::dispatch($product)->afterCommit()->onQueue('default');
            }
        }

        return 0;
    }
}
