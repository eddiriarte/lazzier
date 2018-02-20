<?php

namespace Tests\Tasks;

use Lazzier\Tasks\MakeDir;
use PHPUnit\Framework\TestCase;
use \phpmock\phpunit\PHPMock;
use Lazzier\Exceptions\IncompleteTaskException;

class MakeDirTest extends TestCase
{
    use PHPMock;

    protected $mock;

    public function setUp()
    {
        $this->mock = $this->getFunctionMock('Lazzier\\Tasks', 'mkdir');
    }

    /**
     * @test
     * @dataProvider provideTaskParameters
     */
    public function it_gives_params(MakeDir $task, array $expected)
    {
        $params = $task->params();

        $this->assertEquals(3, count($params));
        $this->assertEquals($expected['target'], $params['target']);
        $this->assertEquals($expected['mode'], $params['mode']);
        $this->assertEquals($expected['recursive'], $params['recursive']);
    }

    public function provideTaskParameters()
    {
        return [
            [
                new MakeDir('/test/path/target'),
                ['target' => '/test/path/target', 'mode' => 0755, 'recursive' => true],
            ],
            [
                new MakeDir('/test/path/target', 0700),
                ['target' => '/test/path/target', 'mode' => 0700, 'recursive' => true],
            ],
            [
                new MakeDir('/test/path/target', 0700, false),
                ['target' => '/test/path/target', 'mode' => 0700, 'recursive' => false],
            ],
        ];
    }

    /**
     * @test
     */
    public function it_invokes_make_dir()
    {
        $this->mock->expects($this->once())->willReturn(true);
        $task = new MakeDir('/test/path/target', 0775);

        $succeed = $task->invoke();

        $this->assertTrue($succeed);
    }

    /**
     * @test
     * @dataProvider provideMakeDirExceptions
     */
    public function it_throws_proper_exceptions($errorMsg, $expectedMsg)
    {
        $this->mock
            ->expects($this->once())
            ->willReturnCallback(function ($trg, $mod, $rec) use ($errorMsg) {
                return trigger_error($errorMsg);
            });

        $task = new MakeDir('/test/path/target', 0775);

        try {
            $succeed = $task->invoke();
        } catch (IncompleteTaskException $e) {
            $this->assertEquals($expectedMsg, $e->getMessage());
            return;
        }

        $this->fail('It should throw a proper exception');
    }

    public function provideMakeDirExceptions()
    {
        return [
            ['mkdir(): File exists', 'Directory already exists!'],
            ['mkdir(): Permission denied', 'Insufficient permissions to create directory!'],
            ['mkdir(): No such file or directory', 'Parent directory does not exists!'],
            ['mkdir(): Just for fun', 'mkdir(): Just for fun'],
        ];
    }
}
