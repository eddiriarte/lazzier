<?php

namespace Tests\Tasks;

use Lazzier\Tasks\ArtisanUp;
use PHPUnit\Framework\TestCase;
use \phpmock\phpunit\PHPMock;

class ArtisanUpTest extends TestCase
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
    public function it_invokes_artisan_up()
    {
        $output = null;
        $return_var = null;
        $this->mock->expects($this->once())->willReturnCallback(
            function ($command) use (&$output, &$return_var) {
                $this->assertEquals("php /foo/bar/root/artisan up", $command);
                $output = ["Artisan is going up!"];
                $return_var = 0;
            }
        );

        $task = new ArtisanUp('/foo/bar/root');

        $succeed = $task->invoke();

        $this->assertEquals(['Artisan is going up!'], $output);
        $this->assertEquals(0, $return_var);
    }
}
