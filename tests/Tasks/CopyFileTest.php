<?php

namespace Tests\Tasks;

use Lazzier\Exceptions\IncompleteTaskException;
use Lazzier\Tasks\CopyFile;
use PHPUnit\Framework\TestCase;
use \phpmock\phpunit\PHPMock;

class CopyFileTest extends TestCase
{
    use PHPMock;

    protected $mock;

    public function setUp()
    {
        $this->mock = $this->getFunctionMock('Lazzier\\Tasks', 'copy');
    }

    /**
     * @test
     */
    public function it_gives_params()
    {
        $task = new CopyFile('/test/path/source', '/foo/bar/target');

        $params = $task->params();

        $this->assertEquals(2, count($params));
        $this->assertEquals('/test/path/source', $params['source']);
        $this->assertEquals('/foo/bar/target', $params['target']);
    }

    /**
     * @test
     */
    public function it_invokes_copy_file()
    {
        $this->mock->expects($this->once())->willReturn(true);
        $task = new CopyFile('/test/path/source', '/foo/bar/target');

        $succeed = $task->invoke();

        $this->assertTrue($succeed);
    }

    /**
     * @test
     * @dataProvider provideCopyFileExceptions
     */
    public function it_throws_proper_exceptions($errorMsg, $expectedMsg)
    {
        $this->mock
            ->expects($this->once())
            ->willReturnCallback(function ($src, $trg) use ($errorMsg) {
                return trigger_error($errorMsg);
            });

        $task = new CopyFile('/test/path/source', '/foo/bar/target');
        try {
            $task->invoke();
        } catch (IncompleteTaskException $e) {
            $this->assertEquals($expectedMsg, $e->getMessage());
            return;
        }

        $this->fail('It should throw a proper exception!');
    }

    public function provideCopyFileExceptions()
    {
        return [
            [
                'copy(): failed to open stream: Permission denied',
                'Insufficient permissions to copy file!',
            ],
            [
                'copy(/test/path/source): failed to open stream: No such file or directory',
                'File to copy does not exists!',
            ],
            [
                'copy(/foo/bar/target): failed to open stream: No such file or directory',
                'Parent directory does not exists!',
            ],
            [
                'copy(): failed to open stream: Another weird error',
                'copy(): failed to open stream: Another weird error',
            ],
        ];
    }
}
