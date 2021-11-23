<?php

namespace Decotatoo\WoocommerceIntegration\Services\BinPacker;

use Decotatoo\WoocommerceIntegration\Models\WiBin;
use DVDoug\BoxPacker\Packer as BoxPackerPacker;
use Exception;

class Packer
{
    public static function pack($bins = [], $items = [])
    {
        if (empty($bins)) {
            $bins = WiBin::all();
        }

        if (empty($items) || empty($bins)) {
            throw new Exception("Error No Items provided or Bins available", 1);
        }

        $packer = new BoxPackerPacker();

        foreach ($bins as $bin) {
            $packer->addBox(new Box($bin));
        }

        foreach ($items as $item) {
            $packer->addItem(new Item($item['product']), $item['quantity']);
        }

        return $packer->pack();
    }
}
