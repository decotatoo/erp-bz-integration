<?php

namespace Decotatoo\WoocommerceIntegration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 */
class WiOrder extends Model
{
    use HasFactory;

    protected $table = 'wi_orders';

    protected $timestamps = false;

    public function wiCustomer()
    {
        return $this->belongsTo(WiCustomer::class, 'wi_customer_id');
    }

    public function wiOrderItems()
    {
        return $this->hasMany(WiOrderItem::class);
    }

    public function WiPackingSimulation()
    {
        return $this->hasMany(WiPackingSimulation::class);
    }
}
