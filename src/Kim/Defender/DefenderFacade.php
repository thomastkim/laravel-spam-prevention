<?php

namespace Kim\Defender;

use Illuminate\Support\Facades\Facade;

class DefenderFacade extends Facade
{

    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Defender';
    }
}
