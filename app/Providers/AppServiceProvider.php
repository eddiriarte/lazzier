<?php

namespace Lazzier\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use LaravelZero\Framework\Container;
use Lazzier\Contracts\ConfigContract;
use Lazzier\Contracts\SystemPathContract;
use Lazzier\Contracts\TaskFactoryContract;
use Lazzier\Services\LazzierConfig;
use Lazzier\Services\SystemPath;
use Lazzier\Services\TaskFactory;

/**
 * @codeCoverageIgnore
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*if (!config('production')) {
        $this->app->register(Laravel\Tinker\TinkerServiceProvider::class);
        }*/

        $this->app->singleton(TaskFactoryContract::class, function ($app) {
            $container = $app instanceof Container ? $app : $app->getLaravel();

            return new TaskFactory($container);
        });

        $this->app->singleton(ConfigContract::class, function () {
            return new LazzierConfig();
        });

        $this->app->singleton(SystemPathContract::class, function () {
            return new SystemPath();
        });
    }
}
