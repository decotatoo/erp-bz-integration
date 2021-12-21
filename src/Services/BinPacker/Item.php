<?php

namespace Decotatoo\Bz\Services\BinPacker;

use App\Models\ProductInCatalog;
use DVDoug\BoxPacker\Item as BoxPackerItem;
use DVDoug\BoxPacker\Rotation;

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

    /**
     * @var Rotation
     */
    private $allowedRotation;

    public function __construct(ProductInCatalog $product)
    {
        $this->product = $product;

        if ($this->product->unitBox()->exists()) {
            $dimension = $this->product->unitBox;
            
            $this->width = $dimension->width;
            $this->length = $dimension->length;
            $this->depth = $dimension->height;
        } elseif ($this->product->boxType()->exists()) {
            $dimension = $this->product->boxType;

            $this->width = intval($dimension->lebar * 10);
            $this->length = intval($dimension->panjang * 10);
            $this->depth = intval($dimension->tinggi * 10);
        }

        $this->weight = $this->product->packed_weight ?? $this->product->gross_weight;
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

    public function getAllowedRotations(): int
    {
        return 6;
        return $this->allowedRotation;
    }

    public function jsonSerialize()
    {
        return [
            'description' => $this->getDescription(),
            'width' => $this->getWidth(),
            'length' => $this->getLength(),
            'depth' => $this->getDepth(),
            'weight' => $this->getWeight(),
            'allowedRotation' => $this->getAllowedRotations(),
        ];
    }
}
