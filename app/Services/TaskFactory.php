<?php

namespace Lazzier\Services;

use Illuminate\Contracts\Foundation\Application;
use Lazzier\Contracts\TaskContract;
use Lazzier\Contracts\TaskFactoryContract;

/**
 * Class TaskFactory
 * @package Lazzier\Services
 */
class TaskFactory implements TaskFactoryContract
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     * @param string $task
     * @param array $inputs
     * @return TaskContract
     */
    public function get(string $task, array $inputs = []): TaskContract
    {
        if (!$this->exists($task)) {
            return $this->app->make("Lazzier\Tasks\ConsoleCmd", ['command' => $task]);
        }

        return $this->app->make("Lazzier\Tasks\\$task", $inputs);
    }

    /**
     * @inheritdoc
     * @param string $task
     * @return bool
     */
    public function exists(string $task): bool
    {
        $className = "Lazzier\Tasks\\$task";
        return class_exists($className);
    }
}
