<?php

namespace Tests\Services;

include_once 'function-mocks.php';

use Lazzier\Services\SystemPath;
use PHPUnit\Framework\TestCase;

class SystemPathTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    /**
     * @test
     */
    public function it_gives_work_directory()
    {
        $service = new SystemPath();

        $path = $service->workDirectory();

        $this->assertEquals('/root/path/foo/', $path);
    }

    /**
     * @test
     */
    public function it_gives_home_directory_from_env()
    {
        $mock = $this->getFunctionMock("Lazzier\\Services", 'getenv');
        $mock->expects($this->once())->willReturn('/home/foo');

        $service = new SystemPath();
        $path = $service->homeDirectory();

        $this->assertEquals('/home/foo', $path);
    }

    /**
     * @test
     */
    public function it_gives_home_directory_from_globals()
    {
        $mock = $this->getFunctionMock("Lazzier\\Services", 'getenv');
        $mock->expects($this->any())->willReturn(null);

        $_SERVER[SystemPath::HOMEDRIVE_GLOBAL] = 'C:\\';
        $_SERVER[SystemPath::HOMEPATH_GLOBAL] = 'Users\\foo\\';

        $service = new SystemPath();
        $path = $service->homeDirectory();

        $this->assertEquals('C:\\Users\\foo', $path);
        unset($_SERVER[SystemPath::HOMEDRIVE_GLOBAL]);
        unset($_SERVER[SystemPath::HOMEPATH_GLOBAL]);
    }

    /**
     * @test
     */
    public function it_gives_bare_file_name()
    {
        $service = new SystemPath();
        $name = $service->fileName('/baz/bar/foo.conf.php');

        $this->assertEquals('foo.conf.php', $name);
    }

    /**
     * @test
     * @dataProvider provideAbsolutePaths
     */
    public function it_gives_absolute_file_path($path, $expected)
    {
        $getenv = $this->getFunctionMock("Lazzier\\Services", 'getenv');
        $getenv->expects($this->any())->willReturn('/home/path');

        $service = new SystemPath();
        $absolutePath = $service->absolute($path);

        $this->assertEquals($expected, $absolutePath);
    }

    public function provideAbsolutePaths()
    {
        return [
            ['../test.txt', '/root/path/foo/../test.txt'],
        ];
    }
}
