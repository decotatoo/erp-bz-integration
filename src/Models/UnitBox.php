<?php

namespace Decotatoo\Bz\Models;

use App\Models\ProductInCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Product Virtual Package Dimension
 * 
 * Dimensions is in integer milimeter.
 */
class UnitBox extends Model
{
    use HasFactory;

    protected $table = 'unit_boxes';

    public function product()
    {
        return $this->hasMany(ProductInCatalog::class, 'unit_box_id', 'id');
    }
}
