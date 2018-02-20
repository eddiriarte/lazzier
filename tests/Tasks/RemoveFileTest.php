<?php

namespace Tests\Tasks;

use Lazzier\Tasks\RemoveFile;
use PHPUnit\Framework\TestCase;
use \phpmock\phpunit\PHPMock;

class RemoveFileTest extends TestCase
{
    use PHPMock;

    protected $mock;

    public function setUp()
    {
        $this->mock = $this->getFunctionMock('Lazzier\\Tasks', 'unlink');
    }

    /**
     * @test
     */
    public function it_invokes_remove_file()
    {
        $this->mock->expects($this->once())->willReturn(true);
        $task = new RemoveFile('/foo/bar/target');

        $succeed = $task->invoke();

        $this->assertTrue($succeed);
    }
}
