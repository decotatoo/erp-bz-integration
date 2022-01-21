<?php

namespace Decotatoo\Bz;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Decotatoo\Bz\Bz
 */
class BzFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bz';
    }
}
