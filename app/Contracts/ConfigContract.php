<?php
namespace Lazzier\Contracts;

/**
 * Interface ConfigContract
 * @package Lazzier\Contracts
 *
 * This is the contract for lazzier configuration files.
 */
interface ConfigContract
{
    /**
     * Gets the path to web's root-directory from configuration.
     */
    public function rootDir(): string;

    /**
     * Gets the path to releases directory from configuration.
     */
    public function releasesDir(): string;

    /**
     * Gets the path to current release symlink(activated release).
     */
    public function releaseLink(): string;

    /**
     * Gets the packaging format of artifact.
     */
    public function packageFormat(): string;

    /**
     * Gets the list of steps to be executed before installation.
     * @param $context
     * @return array
     */
    public function beforeSteps($context = YamlKey::INSTALL): array;

    /**
     * Gets the list of steps to be executed after installation.
     * @param $context
     * @return array
     */
    public function afterSteps($context = YamlKey::INSTALL): array;

    /**
     * Gets the list of steps to be executed on installation.
     *
     * @param $context
     * @return array
     */
    public function schedule($context = YamlKey::INSTALL): array;

    /**
     * Gets the absolute path to target release.
     */
    public function targetRelease();
}
