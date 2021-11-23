<?php

namespace Decotatoo\WoocommerceIntegration\Services\BinPacker;

use Decotatoo\WoocommerceIntegration\Models\WiBin;
use DVDoug\BoxPacker\Box as BoxPackerBox;

class Box implements BoxPackerBox
{
    /**
     * @var WiBin
     */
    public $bin;

    public function __construct(WiBin $bin)
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
        ];
    }
}
