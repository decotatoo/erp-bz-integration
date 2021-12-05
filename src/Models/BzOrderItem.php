<?php

namespace Decotatoo\Bz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 */
class BzOrderItem extends Model
{
    use HasFactory;

    protected $table = 'bz_order_items';

    public function bzOrder()
    {
        return $this->belongsTo(BzOrder::class, 'bz_order_id');
    }

    public function bzProduct()
    {
        return $this->belongsTo(BzProduct::class, 'bz_product_id');
    }
}