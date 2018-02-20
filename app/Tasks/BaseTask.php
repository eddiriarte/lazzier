<?php

namespace Lazzier\Tasks;

use Lazzier\Contracts\TaskContract;
use Lazzier\Exceptions\ErrorHandlerNotFoundException;

abstract class BaseTask implements TaskContract
{
    protected $description = 'Abstract base task.';

    public function name(): string
    {
        $fullyQualifiedClassName = get_class($this);
        return str_replace('Lazzier\\Tasks\\', '', $fullyQualifiedClassName);
    }

    public function desc(): string
    {
        return $this->description;
    }

    abstract public function invoke(): bool;
}
