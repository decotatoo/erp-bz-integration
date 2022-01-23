<?php

namespace Decotatoo\Bz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingSimulation extends Model
{
    use HasFactory;

    protected $table = 'packing_simulations';

    public function bzOrder()
    {
        return $this->belongsTo(BzOrder::class);
    }
}
