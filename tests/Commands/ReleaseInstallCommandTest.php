<?php

namespace Tests\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Lazzier\Commands\ReleaseInstallCommand;
use Lazzier\Contracts\ConfigContract;
use Tests\TestCase;

class ReleaseInstallCommandTest extends TestCase
{
    protected $useSystemPathMock = true;

    protected function registerMethodWorkDirectory() {
        $this->systemPath
        ->expects($this->any())
        ->method('workDirectory')
        ->will($this->returnValue(\getcwd()));
    }

    public function registerTaskBuiltInFunctions()
    {
        $mkdir = $this->getFunctionMock('Lazzier\\Tasks', 'mkdir');
        $mkdir->expects($this->any())->willReturn(true);

        $rmdir = $this->getFunctionMock('Lazzier\\Tasks', 'rmdir');
        $mkdir->expects($this->any())->willReturn(true);

        $symlink = $this->getFunctionMock('Lazzier\\Tasks', 'symlink');
        $symlink->expects($this->any())->willReturn(true);

        $copy = $this->getFunctionMock('Lazzier\\Tasks', 'copy');
        $copy->expects($this->any())->willReturn(true);

        $unlink = $this->getFunctionMock('Lazzier\\Tasks', 'unlink');
        $unlink->expects($this->any())->willReturn(true);
        
        $exec = $this->getFunctionMock('Lazzier\\Tasks', 'exec');
        $exec->expects($this->any())->willReturnCallback(function ($command, &$output, &$return_var) {
            $output = [$command];
            $return_var = 0;

            return $command;
        });
    }

    /**
     * @test
     */
    public function it_throws_error_by_missing_config(): void
    {
        $this->registerMethodWorkDirectory();

        $config = app(ConfigContract::class);
        try {
            $this->app->call((new ReleaseInstallCommand($config))->getName());
        } catch (FileNotFoundException $e) {
            $this->assertStringStartsWith('Unable to load release config from:', $e->getMessage());
            return;
        }

        $this->fail('Expected FileNotFoundException was not thrown!');
    }

    /**
     * @test
     */
    public function it_handles_command(): void
    {
        $expectedOutput = 'Wanna see more? Type `php lazzier list`';
        $this->registerMethodWorkDirectory();
        $this->registerTaskBuiltInFunctions();

        $config = app(ConfigContract::class);
        $command = new ReleaseInstallCommand($config);

        $this->app->call($command->getName(), [
            '--artifact' => 'test.tar.gz',
        ]);

        $this->assertTrue(strpos($this->app->output(), $expectedOutput) !== false);
    }
}
