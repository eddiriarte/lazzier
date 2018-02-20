<?php
/**
 *
 * User: eiriarte
 * Date: 10.01.18
 * Time: 12:00
 */

namespace Lazzier\Contracts;

/**
 * Class InstallScheduler
 * @package Lazzier\Services
 */
interface TaskSchedulerContract
{
    public function tasksToRunBefore();

    public function tasksToRun();

    public function tasksToRunAfter();
}
