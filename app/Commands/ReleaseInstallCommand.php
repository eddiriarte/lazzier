<?php

namespace Lazzier\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Lazzier\Commands\BaseCommand;
use Lazzier\Contracts\ConfigContract;
use Lazzier\Services\InstallScheduler;
use Lazzier\Services\TaskExecutor;

/**
 * Class ReleaseInstallCommand
 * @package Lazzier\Commands
 */
class ReleaseInstallCommand extends BaseCommand
{
    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected $signature = 'release:install
        {--C|config=lazzier.conf.yml} : Path to config file to be used
        {--A|artifact=} : Name of artifact archive';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Installs and enables a release version';

    /**
     * A configuration instance holding all environment properties.
     *
     * @var ConfigContract
     */
    protected $config;

    public function __construct(ConfigContract $config)
    {
        parent::__construct();
        $this->config = $config;
    }

    public function prepare()
    {
        parent::prepare();
        $this->config->load($this->option('config'));
        $this->config->setOptions([
            'artifact' => $this->option('artifact'),
        ]);
    }

    /**
     * Execute the command. Here goes the code.
     *
     * @return void
     */
    public function handle(): void
    {
        $scheduler = new InstallScheduler($this->config);
        $executor = new TaskExecutor($scheduler, $this->getOutput());

        $executor->execute();

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
