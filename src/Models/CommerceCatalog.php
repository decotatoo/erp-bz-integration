<?php

namespace Decotatoo\Bz\Models;

use App\Models\ProductInCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 */
class CommerceCatalog extends Model
{
    use HasFactory;

    protected $table = 'commerce_catalogs';
    
    public $timestamps = false;

    public function products()
    {
        return $this->hasMany(ProductInCatalog::class, 'commerce_catalog_id', 'id');
    }

}