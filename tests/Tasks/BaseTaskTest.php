<?php

namespace Tests\Tasks;

use Lazzier\Contracts\TaskContract;
use Lazzier\Exceptions\IncompleteTaskException;
use Lazzier\Tasks\ArtisanDown;
use Lazzier\Tasks\BaseTask;
use Lazzier\Tasks\ConsoleCmd;
use Lazzier\Tasks\CopyFile;
use Lazzier\Tasks\Symlink;
use PHPUnit\Framework\TestCase;

class BaseTaskTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideTaskAndName
     */
    public function it_should_have_name(TaskContract $task, string $expected)
    {
        $this->assertSame($expected, $task->name());
    }

    public function provideTaskAndName()
    {
        return [
            [new Symlink('source', 'target'), 'Symlink'],
            [new CopyFile('source', 'target'), 'CopyFile'],
            [new ArtisanDown('/root'), 'ArtisanDown'],
            [new ConsoleCmd('cmd -foo --bar baz'), 'ConsoleCmd'],
        ];
    }

    /**
     * @test
     * @dataProvider provideTaskAndDescription
     */
    public function it_should_have_description(TaskContract $task, string $expected)
    {
        $this->assertSame($expected, $task->desc());
    }

    public function provideTaskAndDescription()
    {
        return [
            [new Symlink('source', 'target'), 'Add a soft link to given source at predefined target.'],
            [new CopyFile('source', 'target'), 'Copy a given source file to target.'],
            [new ArtisanDown('/root'), 'Set current app into maintenance mode'],
            [new ConsoleCmd('cmd -foo --bar baz'), 'Execute given shell command.'],
        ];
    }

    // /**
    //  * @test
    //  */
    // public function it_throws_exception_if_no_overriden_invocation()
    // {
    //     $task = new IncompleteTask();
    // 
    //     try {
    //         $task->invoke();
    //         $this->fail();
    //     } catch (\BadMethodCallException $e) {
    //         $this->assertEquals('Method needs to be overriden!', $e->getMessage());
    //     }
    // }
}

class IncompleteTask extends BaseTask
{
    public function params(): array
    {
        return [];
    }

    public function invoke(): bool
    {
        throw new IncompleteTaskException('Method needs to be overriden!');
    }
}
