<?php

namespace Decotatoo\WoocommerceIntegration\Models;

use App\Models\ProductInCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 */
class CommerceCategory extends Model
{
    use HasFactory;

    protected $table = 'commerce_categories';
    
    protected $timestamp = false;

    public function wiCategory()
    {
        return $this->morphOne(WiCategory::class, 'categoryable');  
    }

    public function products()
    {
        return $this->hasMany(ProductInCatalog::class);
    }
}