<?php

namespace Decotatoo\WoocommerceIntegration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 */
class WiCategory extends Model
{
    use HasFactory;

    protected $table = 'wi_categories';

    public function categoryable()
    {
        return $this->morphTo();
    }
}