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

    public function product()
    {
        return $this->belongsTo(ProductInCatalog::class, 'product_id', 'id');
    }
}
