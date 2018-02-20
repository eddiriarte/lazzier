<?php

namespace Tests\Tasks;

use Lazzier\Exceptions\IncompleteTaskException;
use Lazzier\Tasks\Symlink;
use PHPUnit\Framework\TestCase;
use \phpmock\phpunit\PHPMock;

class SymlinkTest extends TestCase
{
    use PHPMock;

    protected $mock;

    public function setUp()
    {
        $this->mock = $this->getFunctionMock('Lazzier\\Tasks', 'symlink');
    }

    /**
     * @test
     */
    public function it_has_params()
    {
        $task = new Symlink('/test/path/source', '/foo/bar/target');

        $params = $task->params();

        $this->assertEquals(2, count($params));
        $this->assertEquals('/test/path/source', $params['source']);
        $this->assertEquals('/foo/bar/target', $params['target']);
    }

    /**
     * @test
     */
    public function it_invokes_symlink()
    {
        $this->mock->expects($this->once())->willReturn(true);
        $task = new Symlink('/test/path/source', '/foo/bar/target');

        $succeed = $task->invoke();

        $this->assertTrue($succeed);
    }

    /**
     * @test
     * @dataProvider provideHandableErrors
     */
    public function it_throws_proper_exceptions(string $errorMsg, string $expectedMsg)
    {
        $this->mock
            ->expects($this->once())
            ->willReturnCallback(function ($src, $trg) use ($errorMsg) {
                trigger_error($errorMsg);
            });

        $task = new Symlink('/test/path/source', '/foo/bar/target');

        try {
            $succeed = $task->invoke();
        } catch (IncompleteTaskException $e) {
            $this->assertEquals($expectedMsg, $e->getMessage());
            return;
        }

        $this->fail('It should throw a proper exception!');
    }

    public function provideHandableErrors()
    {
        return [
            ['symlink(): File exists', 'Symlink already exists!'],
            ['symlink(): Permission denied', 'Insufficient permisions to create symlink!'],
            ['symlink(): No such file or directory', 'Parent directory does not exists!'],
            ['symlink(): Dont know what is wrong!', 'symlink(): Dont know what is wrong!'],
        ];
    }
}
