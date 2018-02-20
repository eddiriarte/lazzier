<?php

namespace Tests\Commands;

use Lazzier\Commands\LazzierCommand;
use Tests\TestCase;

class LazzierCommandTest extends TestCase
{
    /**
     * @test
     */
    public function it_checks_the_lazzier_command_output(): void
    {
        $expectedOutput = 'lazzier-cli v' . config('app.version');

        $this->app->call((new LazzierCommand())->getName());

        $this->assertTrue(strpos($this->app->output(), $expectedOutput) !== false);
    }
}
