<?php

namespace Decotatoo\WoocommerceIntegration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 
 * - column UID is a Sales order number with format "SOOLYY-MMXXXX" where "SOOL" is the stand for "Sales Order Online", "YY" is the year of the order and "XXXX" is the order line number. Example: "SOOL19-020001"
 */
class WiOrder extends Model
{
    use HasFactory;

    protected $table = 'wi_orders';

    public $timestamps = false;

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
