<?php

namespace Lazzier\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

/**
 * Class RunCommand
 * 
 * @package Lazzier\Commands
 */
class RunCommand extends Command
{
    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected $signature = 'run
        {--C|config=lazzier.conf.yml}: Path to config file to be used
        {--P|procedure=} :Configured procedure to run e.g "install"
        {--A|artifact=} :Name of artifact archive';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'TODO: Initialize configuration for lazzier command.';

    /**
     * Execute the command. Here goes the code.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->comment('Wanna see more? Type `php release-install init`');
    }
}
