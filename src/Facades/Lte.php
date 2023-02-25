<?php

namespace Fomvasss\Lte3\Facades;

class Lte extends \Illuminate\Support\Facades\Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Fomvasss\Lte3\Lte::class;
    }
}
