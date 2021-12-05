<?php

namespace Decotatoo\Bz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO:PLACEHOLDER
 
 * - column UID is a Sales order number with format "SOOLYY-MMXXXX" where "SOOL" is the stand for "Sales Order Online", "YY" is the year of the order and "XXXX" is the order line number. Example: "SOOL19-020001"
 */
class BzOrder extends Model
{
    use HasFactory;

    protected $table = 'bz_orders';

    public $timestamps = false;

    public function bzCustomer()
    {
        return $this->belongsTo(BzCustomer::class, 'bz_customer_id');
    }

    public function bzOrderItems()
    {
        return $this->hasMany(BzOrderItem::class);
    }

    public function packingSimulation()
    {
        return $this->hasMany(PackingSimulation::class);
    }
}
