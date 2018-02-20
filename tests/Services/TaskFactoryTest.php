<?php
namespace Tests\Services;

use Lazzier\Services\TaskFactory;
use Lazzier\Tasks\ConsoleCmd;
use Lazzier\Tasks\CopyFile;
use Tests\TestCase;

class TaskFactoryTest extends TestCase
{
    protected $factory;

    public function setUp(): void
    {
        parent::setUp();
        $this->factory = new TaskFactory($this->app->getLaravel());
    }

    /**
     * @test
     * @dataProvider provideTaskNames
     * @param $className
     * @param $shouldExists
     */
    public function it_should_check_existent_tasks($className, $shouldExists)
    {
        $exists = $this->factory->exists($className);

        $this->assertEquals($shouldExists, $exists);
    }

    /**
     * @return mixed
     */
    public function provideTaskNames()
    {
        return [
            ['CopyFile', true],
            ['Unknown', false],
        ];
    }

    /**
     * @test
     * @dataProvider provideTaskInstances
     * @param $className
     * @param $params
     * @param $expected
     */
    public function it_should_create_task($className, $params, $expected)
    {
        $task = $this->factory->get($className, $params);

        $this->assertInstanceOf($expected, $task);
    }

    public function provideTaskInstances()
    {
        return [
            ['CopyFile', ['source' => 'here', 'target' => 'there'], CopyFile::class],
            ['Unknown', ['source' => 'here'], ConsoleCmd::class],
        ];
    }
}
