<?php
namespace Tests\Services;

use Lazzier\Contracts\ConfigContract;
use Lazzier\Services\InstallScheduler;
use Tests\TestCase;
use Lazzier\Tasks\ArtisanDown;
use Lazzier\Tasks\Unpack;
use Lazzier\Tasks\ConsoleCmd;
use Lazzier\Tasks\ArtisanUp;
use Lazzier\Tasks\MakeDir;
use Lazzier\Tasks\CopyFile;
use Lazzier\Tasks\Symlink;
use Lazzier\Tasks\RemoveFile;

class InstallSchedulerTest extends TestCase
{
    protected $config;
    protected $scheduler;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = $this->getMockBuilder(ConfigContract::class)
            ->setMethods([
                'rootDir',
                'releasesDir',
                'releaseLink',
                'packageFormat',
                'beforeSteps',
                'afterSteps',
                'schedule',
                'targetRelease',
            ])
            ->getMock();

        $this->scheduler = new InstallScheduler($this->config);
    }

    /**
     * @test
     */
    public function it_should_get_all_before_tasks()
    {
        $this->config
            ->expects($this->once())
            ->method('beforeSteps')
            ->will($this->returnValue([
                [ 'task' => 'ArtisanDown', 'args' => [ 'root' => '/var/www' ] ],
                [ 'task' => 'Unpack', 'args' => [ 'source' => '/var/www/foo.tar.gz', 'target' => '/var/www/releases/foo' ] ],
                [ 'task' => 'ConsoleCmd', 'args' => [ 'command' => 'echo "Hello World!"' ] ],
            ]));

        $tasks = $this->scheduler->tasksToRunBefore();

        $this->assertEquals(3, count($tasks));
        $this->assertInstanceOf(ArtisanDown::class, $tasks[0]);
        $this->assertInstanceOf(Unpack::class, $tasks[1]);
        $this->assertInstanceOf(ConsoleCmd::class, $tasks[2]);
    }

    /**
     * @test
     */
    public function it_should_get_all_after_tasks()
    {
        $this->config
            ->expects($this->once())
            ->method('afterSteps')
            ->will($this->returnValue([
                [ 'task' => 'ArtisanUp', 'args' => [ 'root' => '/var/www' ] ],
                [ 'task' => 'ConsoleCmd', 'args' => [ 'command' => 'echo "Bye World!"' ] ],
            ]));

        $tasks = $this->scheduler->tasksToRunAfter();

        $this->assertEquals(2, count($tasks));
        $this->assertInstanceOf(ArtisanUp::class, $tasks[0]);
        $this->assertInstanceOf(ConsoleCmd::class, $tasks[1]);
    }

    /**
     * @test
     */
    public function it_should_get_install_tasks()
    {
        $this->config
            ->expects($this->once())
            ->method('schedule')
            ->will($this->returnValue([
                [ 'task' => 'MakeDir', 'args' => [ 'target' => '/var/www/bar' ] ],
                [ 'task' => 'CopyFile', 'args' => [ 'source' => '/var/www/foo', 'target' => '/var/www/bar' ] ],
                [ 'task' => 'Symlink', 'args' => [ 'source' => '/var/www/foo', 'target' => '/var/www/bar' ] ],
                [ 'task' => 'RemoveFile', 'args' => [ 'source' => '/var/www/foo' ] ],
                [ 'task' => 'ConsoleCmd', 'args' => [ 'command' => 'echo "Hello World!"' ] ],
            ]));

        $tasks = $this->scheduler->tasksToRun();

        $this->assertEquals(5, count($tasks));
        $this->assertInstanceOf(MakeDir::class, $tasks[0]);
        $this->assertInstanceOf(CopyFile::class, $tasks[1]);
        $this->assertInstanceOf(Symlink::class, $tasks[2]);
        $this->assertInstanceOf(RemoveFile::class, $tasks[3]);
        $this->assertInstanceOf(ConsoleCmd::class, $tasks[4]);
    }
}
