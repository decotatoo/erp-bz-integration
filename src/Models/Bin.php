<?php

namespace Decotatoo\Bz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Master Box
 * 
 * 
 * Dimensions is in integer milimeter.
 * Max weight is in integer gram.
 */
class Bin extends Model
{
    use HasFactory;

    protected $table = 'bins';
}