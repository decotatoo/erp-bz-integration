<?php

namespace Decotatoo\WoocommerceIntegration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 */
class WiPackingSimulation extends Model
{
    use HasFactory;

    protected $table = 'wi_packing_simulations';

    public function wiOrder()
    {
        return $this->belongsTo(WiOrder::class, 'wp_order_id', 'wp_order_id');
    }
}
