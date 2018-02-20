<?php

namespace Lazzier\Commands;

use Lazzier\Commands\BaseCommand;

/**
 * Class LazzierCommand
 * @package Lazzier\Commands
 */
class LazzierCommand extends BaseCommand
{
    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected $signature = 'lazzier';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Simple console app to perform configurable release installations and rollbacks.';

    /**
     * Execute the command. Here goes the code.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->comment('Wanna see more? Type `php lazzier list`');
    }
}
