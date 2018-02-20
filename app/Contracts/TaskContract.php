<?php
/**
 * TaskContract.php
 *
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
namespace Lazzier\Contracts;

/**
 * Interface TaskContract
 * @package Lazzier\Contracts
 */
interface TaskContract
{
    /**
     * The name of the task.
     *
     * @return string name
     */
    public function name(): string;

    /**
     * The description of the task.
     *
     * @return string description
     */
    public function desc(): string;

    /**
     * Performs execution of the task.
     *
     * @return bool success
     */
    public function invoke(): bool;

    /**
     * Gives the list of parameters set.
     *
     * @return array params
     */
    public function params(): array;
}
