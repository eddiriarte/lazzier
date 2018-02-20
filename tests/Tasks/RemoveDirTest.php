<?php

namespace Tests\Tasks;

use Lazzier\Tasks\RemoveDir;
use PHPUnit\Framework\TestCase;
use \phpmock\phpunit\PHPMock;

class RemoveDirTest extends TestCase
{
    use PHPMock;

    protected $mock;

    public function setUp()
    {
        $this->mock = $this->getFunctionMock('Lazzier\\Tasks', 'rmdir');
    }

    /**
     * @test
     */
    public function it_invokes_remove_dir()
    {
        $this->mock->expects($this->once())->willReturn(true);
        $task = new RemoveDir('/test/path/source');

        $succeed = $task->invoke();

        $this->assertTrue($succeed);
    }
}
