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

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function bzOrders()
    {
        return $this->hasMany(BzOrder::class);
    }
}
