<?php

namespace Tests\Tasks;

use Lazzier\Tasks\Unpack;
use PHPUnit\Framework\TestCase;
use \phpmock\phpunit\PHPMock;

class UnpackTest extends TestCase
{
    use PHPMock;

    protected $exec;
    protected $mkdir;

    public function setUp()
    {
        $this->exec = $this->getFunctionMock('Lazzier\\Tasks', 'exec');
        $this->mkdir = $this->getFunctionMock('Lazzier\\Tasks', 'mkdir');
    }

    /**
     * @test
     */
    public function it_invokes_unlink()
    {
        $this->mkdir->expects($this->once())->willReturn(true);
        $this->exec->expects($this->once())->willReturnCallback(
            function ($command, &$output, &$return_var) {
                $this->assertEquals("tar -xzvf /test/path/source.tar.gz -C /foo/bar/target", $command);
                $output = ['...'];
                $return_var = 0;
            }
        );

        $task = new Unpack('/test/path/source.tar.gz', '/foo/bar/target');

        $succeed = $task->invoke();

        $this->assertTrue($succeed);
    }
}
