<?php

namespace Tests;

use Lazzier\Contracts\SystemPathContract;
use Lazzier\Services\SystemPath;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, MocksSystemPath;

    /**
     * The Laravel Zero application instance.
     *
     * @var \LaravelZero\Framework\Contracts\Application
     */
    protected $app;

    /**
     * A mock to SystemPath
     *
     * @var
     */
    protected $systemPath;

    protected $useSystemPathMock = false;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        $this->app = $this->createApplication();

        if ($this->useSystemPathMock) {
            $this->systemPath = $this->createSystemPathMock();
            app()->singleton(SystemPathContract::class, function () {
                return $this->systemPath;
            });
        }
    }
}
