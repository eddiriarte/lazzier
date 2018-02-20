<?php

namespace Lazzier\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Lazzier\Commands\BaseCommand;
use Lazzier\Services\InstallScheduler;
use Lazzier\Services\LazzierConfig;

/**
 * Class ReleaseUninstallCommand
 * @package Lazzier\Commands
 */
class ReleaseUninstallCommand extends BaseCommand
{
    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected $signature = 'release:uninstall
        {--C|config=lazzier.conf.yml} : Path to config file to be used';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Installs and enables a release version';

    /**
     * A configuration instance holding all environment properties.
     *
     * @var LazzierConfig
     */
    protected $config;

    public function __construct(LazzierConfig $config)
    {
        parent::__construct();
        $this->config = $config;
    }

    public function prepare()
    {
        $this->config->load($this->option('config'));
    }

    /**
     * Execute the command. Here goes the code.
     *
     * @return void
     */
    public function handle(): void
    {
        $scheduler = new InstallScheduler($this->config);

        $this->comment('Wanna see more? Type `php lazzier list`');
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
