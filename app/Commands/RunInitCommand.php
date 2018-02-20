<?php

namespace Lazzier\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Lazzier\Contracts\YamlKey;
use Lazzier\Services\ConfigBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class RunInitCommand
 * @package Lazzier\Commands
 */
class RunInitCommand extends BaseCommand
{
    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected $signature = 'run:init';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Interactive setup of lazzier configuration file.';

    /**
     * Execute the command. Here goes the code.
     *
     * @return void
     */
    public function handle(): void
    {
        $builder = new ConfigBuilder($this);

        $builder
            ->configure(YamlKey::ROOT_DIR)
            ->configure(YamlKey::RELEASES_DIR)
            ->configure(YamlKey::RELEASE_LINK)
            ->configureInstall();

        print_r($builder->toYaml());
    }
}
