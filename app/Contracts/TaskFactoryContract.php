<?php
/**
 * TaskFactoryContract.php
 *
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
namespace Lazzier\Contracts;

/**
 * Interface TaskFactoryContract
 * @package Lazzier\Contracts
 */
interface TaskFactoryContract
{
    /**
     * @param string $task
     * @param array $inputs
     *
     * @return Lazzier\Contracts\TaskContract
     */
    public function get(string $task, array $inputs = []): TaskContract;

    /**
     * @param string $task
     *
     * @return bool
     */
    public function exists(string $task): bool;
}
