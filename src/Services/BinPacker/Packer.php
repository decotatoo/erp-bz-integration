<?php

namespace Decotatoo\Bz\Services\BinPacker;

use Decotatoo\Bz\Models\Bin;
use DVDoug\BoxPacker\InfalliblePacker;
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

        $packer = new InfalliblePacker();
        // $packer->setMaxBoxesToBalanceWeight(3);

        foreach ($bins as $bin) {
            $packer->addBox(new Box($bin));
        }

        foreach ($items as $item) {
            $packer->addItem(new Item($item['product']), $item['quantity']);
        }

        $packed = array_map(function ($pb) {
            $_pb = $pb->jsonSerialize();
            $_pb['box'] = $pb->getBox()->jsonSerialize();

            $_pb['weight'] = $pb->getWeight();
            $_pb['volume'] = $pb->getBox()->getOuterDepth() * $pb->getBox()->getOuterWidth() * $pb->getBox()->getOuterLength();
            $_pb['items'] = [];

            foreach (iterator_to_array($pb->getItems()) as $item) {
                $_item = $item->jsonSerialize();
                $_item['id'] = $item->getItem()->product->prod_id;
                $_item['item']['id'] = $item->getItem()->product->id;
                $_item['item']['name'] = $item->getItem()->product->prod_name;

                $_pb['items'][] = $_item;
            }

            return $_pb;
        }, $packer->pack()->jsonSerialize());

        $unpackedItems = array_map(function ($item) {
            $_item = $item->jsonSerialize();
            $_item['id'] = $item->product->prod_id;
            $_item['item']['id'] = $item->product->id;
            $_item['item']['name'] = $item->product->prod_name;

            return $_item;
        }, iterator_to_array($packer->getUnpackedItems()));

        return [
            'packed' => $packed,
            'unpacked' => $unpackedItems,
        ];
    }
}
