<?php

namespace Tests\Services;

use Lazzier\Services\LazzierConfig;
use Tests\TestCase;

/**
 * Class LazzierConfigTest
 * @package Tests\Unit
 */
class LazzierConfigTest extends TestCase
{
    protected $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = new LazzierConfig();
    }

    protected function loadConfigFile()
    {
        $path = dirname(__FILE__) . '/lazzier-config-test.yml';
        $this->config->load($path);
    }

    /**
     * @test
     */
    public function it_should_load_root_dir()
    {
        $this->loadConfigFile();

        $rootDir = $this->config->rootDir();

        $this->assertEquals('/var/www', $rootDir);
    }

    /**
     * @test
     */
    public function it_should_load_current_link()
    {
        $this->loadConfigFile();

        $currentLink = $this->config->releaseLink();

        $this->assertEquals('/var/www/current', $currentLink);
    }

    /**
     * @test
     */
    public function it_should_load_releases_dir()
    {
        $this->loadConfigFile();

        $releasesDir = $this->config->releasesDir();

        $this->assertEquals('/var/www/releases', $releasesDir);
    }

    /**
     * @test
     */
    public function it_should_load_before_install_steps()
    {
        $this->loadConfigFile();

        $steps = $this->config->beforeSteps();

        $this->assertCount(3, $steps);
    }

    /**
     * @test
     */
    public function it_should_load_after_install_steps()
    {
        $this->loadConfigFile();

        $steps = $this->config->afterSteps();

        $this->assertCount(2, $steps);
    }

    /**
     * @test
     */
    public function it_should_load_install_steps()
    {
        $this->loadConfigFile();
        $this->config->setOptions([
            'artifact' => 'release_v1.0.0.tar.gz',
        ]);

        $steps = $this->config->schedule();

        $this->assertCount(7, $steps);
    }
}
