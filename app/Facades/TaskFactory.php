<?php
namespace Lazzier\Facades;

use Illuminate\Support\Facades\Facade;
use Lazzier\Contracts\TaskFactoryContract;

class TaskFactory extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return TaskFactoryContract::class;
    }
}
