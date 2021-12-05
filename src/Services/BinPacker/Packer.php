<?php

namespace Decotatoo\Bz\Services\BinPacker;

use Decotatoo\Bz\Models\Bin;
use DVDoug\BoxPacker\PackedBox;
use DVDoug\BoxPacker\Packer as BoxPackerPacker;
use Exception;

class Packer
{
    public static function pack($bins = [], $items = [])
    {
        if (empty($bins)) {
            $bins = Bin::all();
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

        return array_map(function ($packedBox) {
            /** @var PackedBox $packedBox */
            return [
                'bin' => $packedBox->getBox()->getReference(),
                'items' => $packedBox->getItems()->asItemArray(),
                'weight' => $packedBox->getWeight(),
                'volume' => $packedBox->getBox()->getOuterDepth() * $packedBox->getBox()->getOuterWidth() * $packedBox->getBox()->getOuterLength(),
            ];

        }, $packer->pack()->jsonSerialize());
    }
}
