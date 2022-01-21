<?php

namespace Decotatoo\Bz\Services\BinPacker;

use Decotatoo\Bz\Models\Bin;
use DVDoug\BoxPacker\Box as BoxPackerBox;

class Box implements BoxPackerBox
{
    /**
     * @var Bin
     */
    public $bin;

    public function __construct(Bin $bin)
    {
        $this->bin = $bin;
    }

    public function getReference(): string
    {
        return $this->bin->ref;
    }

    public function getOuterWidth(): int
    {
        return $this->bin->outer_width;
    }

    public function getOuterLength(): int
    {
        return $this->bin->outer_length;
    }

    public function getOuterDepth(): int
    {
        return $this->bin->outer_depth;
    }

    public function getEmptyWeight(): int
    {
        return $this->bin->empty_weight;
    }

    public function getInnerWidth(): int
    {
        return $this->bin->inner_width;
    }

    public function getInnerLength(): int
    {
        return $this->bin->inner_length;
    }

    public function getInnerDepth(): int
    {
        return $this->bin->inner_depth;
    }

    public function getMaxWeight(): int
    {
        return $this->bin->max_weight;
    }

    public function jsonSerialize()
    {
        return [
            'reference' => $this->getReference(),
            'outer_width' => $this->getOuterWidth(),
            'outer_length' => $this->getOuterLength(),
            'outer_depth' => $this->getOuterDepth(),
            'empty_weight' => $this->getEmptyWeight(),
            'inner_width' => $this->getInnerWidth(),
            'inner_length' => $this->getInnerLength(),
            'inner_depth' => $this->getInnerDepth(),
            'max_weight' => $this->getMaxWeight(),
            'inner_volume' => $this->getInnerVolume(),
            'outer_volume' => $this->getOuterVolume(),
        ];
    }

    public function getInnerVolume(): int
    {
        return $this->getInnerWidth() * $this->getInnerLength() * $this->getInnerDepth();
    }

    public function getOuterVolume(): int
    {
        return $this->getOuterWidth() * $this->getOuterLength() * $this->getOuterDepth();
    }
}
