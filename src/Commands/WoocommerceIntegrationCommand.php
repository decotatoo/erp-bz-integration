<?php

namespace Decotatoo\WoocommerceIntegration\Commands;

use Illuminate\Console\Command;

class WoocommerceIntegrationCommand extends Command
{
    public $signature = 'wi';

    public $description = 'WooCommerce Integration command';

    public function handle(): int
    {
        $this->comment('woocommerce integration');

        return self::SUCCESS;
    }
}
