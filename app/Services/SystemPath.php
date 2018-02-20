<?php

namespace Lazzier\Services;

use Lazzier\Contracts\SystemPathContract;

/**
 * Class SystemPath
 * @package Lazzier\Services
 */
class SystemPath implements SystemPathContract
{
    const HOMEPATH_GLOBAL = 'HOMEPATH';
    const HOMEDRIVE_GLOBAL = 'HOMEDRIVE';
    const HOME_ENV = 'HOME';

    /**
     * @inheritDoc
     */
    public function workDirectory(): string
    {
        return getcwd() . '/';
    }

    /**
     * @inheritDoc
     */
    public function homeDirectory(): string
    {
        $home = $this->home();
        if (!empty($home)) {
            $home = rtrim($home, '/');
        } elseif (!empty($this->homeDrive()) && !empty($this->homePath())) {
            $home = $this->homeDrive() . $this->homePath();
            $home = rtrim($home, '\\/');
        }

        return empty($home) ? '' : $home;
    }

    /**
     * @inheritDoc
     */
    public function absolute(string $path): string
    {
        if (0 === strpos('~', $path)) {
            return $this->homeDirectory() . ltrim($path, '~');
        }

        return substr($path, 0, 1) === '/' ? $path : $this->workDirectory() . $path;
    }

    /**
     * @inheritDoc
     */
    public function fileName(string $path): string
    {
        $index = strrpos($path, '/');

        return (false === $index) ? $path : substr($path, $index + 1);
    }

    /**
     * @return mixed
     */
    protected function homePath()
    {
        return $_SERVER[self::HOMEPATH_GLOBAL];
    }

    /**
     * @return array|false|string
     */
    protected function home()
    {
        return getenv(self::HOME_ENV);
    }

    /**
     * @return mixed
     */
    protected function homeDrive()
    {
        return $_SERVER[self::HOMEDRIVE_GLOBAL];
    }
}
