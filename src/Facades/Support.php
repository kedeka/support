<?php

namespace Kedeka\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kedeka\Support\Support
 */
class Support extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Kedeka\Support\Support::class;
    }
}
