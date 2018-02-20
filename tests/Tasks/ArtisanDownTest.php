<?php

namespace Tests\Tasks;

use Lazzier\Tasks\ArtisanDown;
use PHPUnit\Framework\TestCase;
use \phpmock\phpunit\PHPMock;

class ArtisanDownTest extends TestCase
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
    public function it_invokes_artisan_down()
    {
        $output = null;
        $return_var = null;
        $this->mock->expects($this->once())->willReturnCallback(
            function ($command) use (&$output, &$return_var) {
                $this->assertEquals("php /foo/bar/root/artisan down", $command);
                $output = ["Artisan is going down!"];
                $return_var = 0;
            }
        );

        $task = new ArtisanDown('/foo/bar/root');

        $succeed = $task->invoke();

        $this->assertEquals(['Artisan is going down!'], $output);
        $this->assertEquals(0, $return_var);
    }
}
