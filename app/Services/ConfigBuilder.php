<?php
namespace Lazzier\Services;

use Illuminate\Console\Command;
use Lazzier\Contracts\CommandContract;
use Lazzier\Contracts\YamlKey;
use Lazzier\Facades\TaskFactory;
use Lazzier\Traits\Interrogator;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigBuilder
 * @package Lazzier\Services
 */
class ConfigBuilder
{
    use Interrogator;

    protected $configuration;

    public function __construct(CommandContract $command)
    {
        $this->command = $command;
        $this->configuration = [];
    }

    public function configure(string $property, string $context = null)
    {
        if (empty($context) && $this->isRootProperty($property)) {
            $this->configuration[$property] = $this->askFor($property);
            return $this;
        } elseif ($this->isKnownContext($context) && $this->isValidContextKey($property)) {
            $this->configuration[$context][$property] = $this->getTasksForSection($property);
            return $this;
        }

        die('WTF! throw a proper exception here!');
    }

    public function configureInstall()
    {
        $this->configure(YamlKey::BEFORE, YamlKey::INSTALL)
            ->configure(YamlKey::SCHEDULE, YamlKey::INSTALL)
            ->configure(YamlKey::AFTER, YamlKey::INSTALL);

        return $this;
    }

    public function configureUninstall()
    {
        $this->configure(YamlKey::BEFORE, YamlKey::UNINSTALL)
            ->configure(YamlKey::SCHEDULE, YamlKey::UNINSTALL)
            ->configure(YamlKey::AFTER, YamlKey::UNINSTALL);

        return $this;
    }

    /**
     * @param string $keyword
     * @return array|null|string
     * @throws \Exception
     */
    public function configureTask(string $keyword)
    {
        $task = null;
        switch ($keyword) {
            case 'command_line':
                $task = $this->askFor('enter_command_line', $keyword);
                break;
            case YamlKey::LINK_CURRENT:
            case YamlKey::UNPACK_ARTIFACT:
                $task = $keyword;
                break;
            case YamlKey::COPY_FILES:
            case YamlKey::LINK_FILES:
            case YamlKey::SHARE_DIRS:
                $task = $this->askWhile($keyword, function () use ($keyword) {
                    $source = $this->askFor('enter_source', $keyword);
                    $target = $this->askFor('enter_target', $keyword);

                    return compact('source', 'target');
                });
                break;
            case YamlKey::MAKE_DIRS:
                $task = $this->askWhile($keyword, function () use ($keyword) {
                    $target = $this->askFor('enter_target', $keyword);

                    return compact('target');
                });
                break;
            default:
                if (!TaskFactory::exists($keyword)) {
                    throw new \Exception('Don\'t know what you want from me!', 1);
                }

                $params = $this->getTaskParams($keyword);
                $task = $this->askTaskParams($keyword, $params);
                break;
        }

        return $task;
    }

    /**
     * @param string $section
     * @return array
     * @throws \Exception
     */
    protected function getTasksForSection(string $section)
    {
        $tasks = [];
        $withTask = $this->shouldAddNewTask($section);
        $choices = array_merge($this->helperTaskOptions(), $this->taskClassOptions());
        while ($withTask) {
            if ($selections = $this->selectTaskForSection($section, $choices)) {
                $tasks[] = $this->configureTask($selections[0]);
            }

            $withTask = $this->shouldAddNewTask($section);
        }

        return $tasks;
    }

    /**
     * @param string $property
     * @return bool
     */
    protected function isRootProperty(string $property)
    {
        return in_array($property, [
            YamlKey::ROOT_DIR,
            YamlKey::RELEASES_DIR,
            YamlKey::RELEASE_LINK,
            YamlKey::PACKAGE_FORMAT,
        ]);
    }

    /**
     * @param string $context
     * @return bool
     */
    protected function isKnownContext(string $context): bool
    {
        return in_array($context, [
            YamlKey::INSTALL,
            YamlKey::UNINSTALL,
        ]);
    }

    /**
     * @param string $key
     * @return bool
     */
    protected function isValidContextKey(string $key): bool
    {
        return in_array($key, [
            YamlKey::BEFORE,
            YamlKey::SCHEDULE,
            YamlKey::AFTER,
        ]);
    }

    /**
     * @return string
     */
    public function toYaml()
    {
        return Yaml::dump($this->configuration, 8);
    }
}
