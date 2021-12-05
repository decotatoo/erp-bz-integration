<?php

namespace Decotatoo\Bz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 */
class BzCategory extends Model
{
    use HasFactory;

    protected $table = 'bz_categories';

    protected $timestamps = false;

    public function categoryable()
    {
        return $this->morphTo();
    }
}