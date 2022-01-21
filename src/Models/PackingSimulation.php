<?php

namespace Decotatoo\Bz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 */
class PackingSimulation extends Model
{
    use HasFactory;

    protected $table = 'packing_simulations';

    public function bzOrder()
    {
        return $this->belongsTo(BzOrder::class, 'wp_order_id', 'wp_order_id');
    }
}
