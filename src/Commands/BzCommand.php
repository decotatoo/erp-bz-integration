<?php

namespace Decotatoo\Bz\Commands;

use Illuminate\Console\Command;

class BzCommand extends Command
{
    public $signature = 'bz';

    public $description = 'WooCommerce Integration command';

    public function handle(): int
    {
        $this->comment('woocommerce integration');

        return self::SUCCESS;
    }
}
