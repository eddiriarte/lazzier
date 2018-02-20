<?php

namespace Tests\Tasks;

use Lazzier\Tasks\ConsoleCmd;
use PHPUnit\Framework\TestCase;
use \phpmock\phpunit\PHPMock;

class ConsoleCmdTest extends TestCase
{
    use PHPMock;

    protected $mock;

    public function setUp()
    {
        $this->mock = $this->getFunctionMock('Lazzier\\Tasks', 'exec');
    }

    /**
     * @test
     */
    public function it_invokes_a_console_command()
    {
        $cmd = "echo 'Hello World!'";
        $output = null;
        $return_var = null;
        $this->mock->expects($this->once())->willReturnCallback(
            function ($command) use ($cmd, &$output, &$return_var) {
                $this->assertEquals($cmd, $command);
                $output = ["Hello World!"];
                $return_var = 0;
            }
        );

        $task = new ConsoleCmd($cmd);

        $succeed = $task->invoke();

        $this->assertEquals(['Hello World!'], $output);
        $this->assertEquals(0, $return_var);
    }
}
