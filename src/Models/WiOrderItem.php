<?php

namespace Decotatoo\WoocommerceIntegration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 */
class WiOrderItem extends Model
{
    use HasFactory;

    protected $table = 'wi_order_items';

    public function wiOrder()
    {
        return $this->belongsTo(WiOrder::class, 'wi_order_id');
    }

    public function wiProduct()
    {
        return $this->belongsTo(WiProduct::class, 'wi_product_id');
    }
}
