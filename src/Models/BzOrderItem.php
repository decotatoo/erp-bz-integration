<?php

namespace Decotatoo\Bz\Models;

use App\Models\ProductStockOut;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 */
class BzOrderItem extends Model
{
    use HasFactory;

    protected $table = 'bz_order_items';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'taxes' => 'array',
        'meta_data' => 'array',
    ];

    public function bzOrder()
    {
        return $this->belongsTo(BzOrder::class, 'bz_order_id');
    }

    public function bzProduct()
    {
        return $this->belongsTo(BzProduct::class, 'bz_product_id');
    }

    public function productStockOuts()
    {
        return $this->morphMany(ProductStockOut::class, 'stockable');
    }
}
