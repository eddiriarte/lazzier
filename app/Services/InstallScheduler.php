<?php
namespace Lazzier\Services;

use Lazzier\Contracts\ConfigContract;
use Lazzier\Contracts\TaskSchedulerContract;
use Lazzier\Contracts\YamlKey;
use Lazzier\Facades\TaskFactory;

/**
 * Class InstallScheduler
 * @package Lazzier\Services
 */
class InstallScheduler implements TaskSchedulerContract
{
    /**
     * @var ConfigContract $config
     */
    protected $config;

    public function __construct(ConfigContract $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function tasksToRunBefore()
    {
        $commands = $this->config->beforeSteps(YamlKey::INSTALL);

        return $this->toTasks($commands);
    }

    public function tasksToRun()
    {
        $commands = $this->config->schedule();

        return $this->toTasks($commands);
    }

    public function tasksToRunAfter()
    {
        $commands = $this->config->afterSteps(YamlKey::INSTALL);

        return $this->toTasks($commands);
    }

    protected function toTasks(array $commands)
    {
        return array_map(function ($entry) {
            return TaskFactory::get($entry['task'], $entry['args']);
        }, $commands);
    }
}
