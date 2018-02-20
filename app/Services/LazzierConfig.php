<?php
/**
 * LazzierConfig.php
 *
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
namespace Lazzier\Services;

use Lazzier\Contracts\ConfigContract;
use Lazzier\Contracts\YamlKey;
use Lazzier\Facades\Path;
use Lazzier\Facades\TaskFactory;
use Lazzier\Traits\FetchInputs;
use Lazzier\Traits\FetchYaml;
use Lazzier\Traits\MapYaml;

/**
 * Class LazzierConfig
 * @package Lazzier\Services
 */
class LazzierConfig implements ConfigContract
{
    use FetchYaml, FetchInputs, MapYaml;

    /**
     * @inheritdoc
     * @return string
     */
    public function rootDir(): string
    {
        return Path::absolute($this->getString(YamlKey::ROOT_DIR));
    }

    /**
     * @inheritDoc
     */
    public function releasesDir(): string
    {
        return Path::absolute($this->getString(YamlKey::RELEASES_DIR));
    }

    /**
     * @inheritDoc
     */
    public function releaseLink(): string
    {
        return Path::absolute($this->getString(YamlKey::RELEASE_LINK));
    }

    /**
     * @inheritDoc
     */
    public function packageFormat(): string
    {
        return $this->getString(YamlKey::PACKAGE_FORMAT);
    }

    /**
     * @inheritDoc
     */
    public function beforeSteps($context = YamlKey::INSTALL): array
    {
        return $this->mapAsTaskWithParameters(
            $this->getArray(YamlKey::BEFORE, $context),
            $this
        );
    }

    /**
     * @param $context
     * @return array
     */
    public function afterSteps($context = YamlKey::INSTALL): array
    {
        return $this->mapAsTaskWithParameters(
            $this->getArray(YamlKey::AFTER, $context),
            $this
        );
    }

    /**
     * @inheritDoc
     */
    public function schedule($context = YamlKey::INSTALL): array
    {
        $steps = $this->getArray(YamlKey::SCHEDULE, $context);

        return array_reduce($steps, function ($carry, $item) {
            return array_merge($carry, $this->makeQualifiedTasks($item));
        }, []);

        return $tasks;
    }

    /**
     * @inheritDoc
     */
    public function targetRelease()
    {
        return "{$this->releasesDir()}/{$this->release()}/";
    }

    /**
     * @param $item
     * @return array
     */
    protected function makeQualifiedTasks($item)
    {
        $target = $this->targetRelease();

        if (!is_array($item)) {
            $item = [$item => null];
        }

        $tasks = [];
        $key = array_keys($item)[0];
        $data = $item[$key];

        if (in_array($key, [YamlKey::UNPACK_ARTIFACT, YamlKey::LINK_CURRENT])) {
            $tasks[] = $this->mapAsTaskWithParameters([$key], $this)[0];
        } elseif (YamlKey::COPY_FILES === $key) {
            foreach ($data as $entry) {
                $parameters = $this->mapAsAbsoluteSourcesAndTarget($entry, $target);
                $tasks[] = [YamlKey::TASK => 'CopyFile', YamlKey::ARGS => $parameters];
            }
        } elseif (YamlKey::LINK_FILES === $key) {
            foreach ($data as $entry) {
                $parameters = $this->mapAsAbsoluteSourcesAndTarget($entry, $target);
                $tasks[] = [YamlKey::TASK => 'Symlink', YamlKey::ARGS => $parameters];
            }
        } elseif (YamlKey::SHARE_DIRS === $key) {
            foreach ($data as $entry) {
                $parameters = $this->mapAsAbsoluteSourcesAndTarget($entry, $target);
                $tasks[] = [YamlKey::TASK => 'Symlink', YamlKey::ARGS => $parameters];
            }
        } elseif (YamlKey::MAKE_DIRS === $key) {
            foreach ($data as $entry) {
                $parameters = $this->mapAsAbsoluteTarget($entry, $target);
                $tasks[] = [YamlKey::TASK => 'MakeDir', YamlKey::ARGS => $parameters];
            }
        } elseif (YamlKey::CUSTOMS === $key) {
            $entries = $this->mapAsTaskWithParameters($data, $this);
            foreach ($entries as $entry) {
                $tasks[] = $entry;
            }
        }

        return $tasks;
    }
}
