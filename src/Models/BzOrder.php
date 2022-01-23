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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'billing' => 'array',
        'shipping' => 'array',
        'shipping_lines' => 'array',
        'line_items' => 'array',
        'tax_lines' => 'array',
        'fee_lines' => 'array',
        'coupon_lines' => 'array',
        'meta_data' => 'array',
    ];

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
        return $this->hasOne(PackingSimulation::class);
    }

    public function getMetaData($key)
    {
        $metaIndex = array_search($key, array_column($this->meta_data, 'key')); 

        if ($metaIndex !== false) {
            return $this->meta_data[$metaIndex]['value'];
        }

        return false;
    }
}
