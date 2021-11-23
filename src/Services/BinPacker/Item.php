<?php

namespace Decotatoo\WoocommerceIntegration\Services\BinPacker;

use App\Models\ProductInCatalog;
use DVDoug\BoxPacker\Item as BoxPackerItem;

class Item implements BoxPackerItem
{
    /**
     * @var ProductInCatalog
     */
    public $product;

    /**
     * @var int
     */
    private $width = 0;

    /**
     * @var int
     */
    private $length = 0;

    /**
     * @var int
     */
    private $depth = 0;

    /**
     * @var int
     */
    private $weight = 0;

    private $keep_flat = false;

    public function __construct(ProductInCatalog $product)
    {
        $this->product = $product;

        if ($this->product->boxType) {
            $boxType = $this->product->boxType;

            $this->width = $this->normalizeDimentionalValue($boxType->lebar);
            $this->length = $this->normalizeDimentionalValue($boxType->panjang);
            $this->depth = $this->normalizeDimentionalValue($boxType->tinggi);
        }

        if ($this->product->packing_weight) {
            $this->weight = $this->product->packing_weight;
        }

        $this->keep_flat = $this->product->keep_flat ?? false;
    }

    /**
     * Convert the dimentional value from cm to mm
     */
    private function normalizeDimentionalValue($value): int
    {
        return intval($value * 10);
    }

    public function getDescription(): string
    {
        return $this->product->prod_id;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getKeepFlat(): bool
    {
        return $this->keep_flat;
    }

    public function jsonSerialize()
    {
        return [
            'description' => $this->description,
            'width' => $this->width,
            'length' => $this->length,
            'depth' => $this->depth,
            'weight' => $this->weight,
            'keepFlat' => $this->keep_flat,
        ];
    }
}
