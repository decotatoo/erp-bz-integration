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
            $_packedBox = $packedBox->jsonSerialize();

            $_packedBox['weight'] = $packedBox->getWeight();
            $_packedBox['volume'] = $packedBox->getBox()->getOuterDepth() * $packedBox->getBox()->getOuterWidth() * $packedBox->getBox()->getOuterLength();
            $_packedBox['items'] = [];

            foreach (iterator_to_array($packedBox->getItems()) as $item) {
                $_item = $item->jsonSerialize();

                $_item['item']['id'] = $item->getItem()->product->id;
                $_item['item']['name'] = $item->getItem()->product->prod_name;
                // $_item['item']['unit_box'] = $item->getItem()->product->unitBox;

                $_packedBox['items'][] = $_item;
            }

            return $_packedBox;
        }, $packer->pack()->jsonSerialize());
    }
}
