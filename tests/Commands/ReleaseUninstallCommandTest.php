<?php

namespace Tests\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Lazzier\Commands\ReleaseUninstallCommand;
use Lazzier\Contracts\ConfigContract;
use Tests\TestCase;

class ReleaseUninstallCommandTest extends TestCase
{
    protected $useSystemPathMock = true;

    /**
     * @test
     */
    public function it_handles_command(): void
    {
        $expectedOutput = 'Wanna see more? Type `php lazzier list`';

        $this->systemPath
            ->expects($this->any())
            ->method('workDirectory')
            ->will($this->returnValue(\getcwd()));

        $config = app(ConfigContract::class);
        $command = new ReleaseUninstallCommand($config);

        $this->app->call($command->getName());

        $this->assertTrue(strpos($this->app->output(), $expectedOutput) !== false);
    }
}
