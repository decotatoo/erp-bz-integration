<?php

namespace Decotatoo\WoocommerceIntegration\Models;

use App\Models\ProductInCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 */
class WiProduct extends Model
{
    use HasFactory;

    protected $table = 'wi_products';

    protected $timestamp = false;

    public function product()
    {
        return $this->belongsTo(ProductInCatalog::class, 'product_id', 'id');
    }

    public function wiOrderItems()
    {
        return $this->hasMany(WiOrderItem::class);
    }
}
