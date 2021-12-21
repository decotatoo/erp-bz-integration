<?php

namespace Decotatoo\Bz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 */
class BzCustomer extends Model
{
    use HasFactory;

    protected $table = 'bz_customers';

    public $timestamps = false;

    public function bzOrders()
    {
        return $this->hasMany(BzOrder::class);
    }

}
