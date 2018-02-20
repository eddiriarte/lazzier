<?php
namespace Lazzier\Traits;

use Lazzier\Contracts\ConfigContract;
use Lazzier\Contracts\TaskArg;
use Lazzier\Contracts\YamlKey;
use Lazzier\Facades\Path;
use Lazzier\Facades\TaskFactory;

/**
 * Trait MapYaml
 * @package Lazzier\Traits
 */
trait MapYaml
{
    /**
     * @param array $entries
     * @param string $prefix
     * @return array
     */
    public function mapAsAbsoluteSourcesAndTarget(array $entries, string $prefix)
    {
        return [
            TaskArg::SOURCE => Path::absolute($entries[YamlKey::SOURCE]),
            TaskArg::TARGET => Path::absolute($prefix . $entries[YamlKey::TARGET]),
        ];
    }

    /**
     * @param array $entries
     * @param string $prefix
     * @return array
     */
    public function mapAsAbsoluteTarget(array $entries, string $prefix)
    {
        return [
            TaskArg::TARGET => Path::absolute($prefix . $entries[YamlKey::TARGET]),
        ];
    }

    /**
     * @param array $entries
     * @param ConfigContract $config
     * @return array
     */
    public function mapAsTaskWithParameters(array $entries, ConfigContract $config)
    {
        return array_map(function ($entry) use ($config) {
            if (!is_array($entry)) {
                return [
                    YamlKey::TASK => $this->findKnownTaskName($entry),
                    YamlKey::ARGS => $this->findKnownTaskArguments($entry, $config),
                ];
            }

            return [
                YamlKey::TASK => $entry[YamlKey::TASK],
                YamlKey::ARGS => array_key_exists(YamlKey::ARGS, $entry)
                ? $entry[YamlKey::ARGS] : $this->findKnownTaskArguments($entry[YamlKey::TASK], $config),
            ];
        }, $entries);
    }

    /**
     * @param string $task
     * @param ConfigContract $config
     * @return array
     */
    public function findKnownTaskArguments(string $task, ConfigContract $config)
    {
        $params = [];
        if (in_array($task, ['ArtisanUp', 'ArtisanDown'])) {
            $params[TaskArg::ROOT] = $config->releaseLink();
        } elseif (YamlKey::UNPACK_ARTIFACT === $task) {
            $params[TaskArg::SOURCE] = $config->artifact();
            $params[TaskArg::TARGET] = $config->targetRelease();
        } elseif (YamlKey::LINK_CURRENT === $task) {
            $params[TaskArg::SOURCE] = $config->targetRelease();
            $params[TaskArg::TARGET] = $config->releaseLink();
        } else {
            $params[TaskArg::COMMAND] = $task;
        }

        return $params;
    }

    /**
     * @param string $task
     * @return array
     */
    public function findKnownTaskName(string $task)
    {
        if (TaskFactory::exists($task)) {
            return $task;
        } elseif (YamlKey::UNPACK_ARTIFACT === $task) {
            return 'Unpack';
        } elseif (YamlKey::LINK_CURRENT === $task) {
            return 'Symlink';
        } else {
            return 'ConsoleCmd';
        }
    }
}
