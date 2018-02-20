<?php

namespace Tests;

use Lazzier\Contracts\SystemPathContract;
use Lazzier\Services\SystemPath;
use \phpmock\phpunit\PHPMock;

trait MocksSystemPath
{
    use PHPMock;

    /**
     * Creates the application and returns it.
     *
     * @return \LaravelZero\Framework\Contracts\Application
     */
    public function createSystemPathMock(): SystemPathContract
    {
        return $this->systemPath = $this->getMockBuilder(SystemPath::class)
            ->setMethods([
                'workDirectory',
                'homeDirectory',
            ])
            ->getMock();
    }
}
