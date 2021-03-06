<?php

namespace Decotatoo\Bz\Models;

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
    
    public $timestamps = false;

    public function bzCategory()
    {
        return $this->morphOne(BzCategory::class, 'categoryable');  
    }

    public function products()
    {
        return $this->hasMany(ProductInCatalog::class, 'commerce_category_id', 'id');
    }

}