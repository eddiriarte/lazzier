<?php
namespace Lazzier\Facades;

use Illuminate\Support\Facades\Facade;
use Lazzier\Contracts\SystemPathContract;

/**
 * Class Path
 * @package Lazzier\Facades
 */
class Path extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SystemPathContract::class;
    }
}
