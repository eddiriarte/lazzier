<?php
namespace Lazzier\Traits;

/**
 * Trait MakeThrowable
 * @package Lazzier\Traits
 */
trait MakeThrowable
{
    /**
     * Activates custom error handling.
     * Needed for some built-in errors that does not throw an exception.
     *
     * @throws ErrorHandlerNotFoundException
     */
    public function enableErrorHandler()
    {
        set_error_handler([$this, 'handleErrors']);
    }

    /**
     * Disables custom error handling.
     */
    public function disableErrorHandler()
    {
        restore_error_handler();
    }

    /**
     * Custom error handler.
     * It converts warnings and errors into a known/catchable exception.
     *
     * @param $errorSeverity
     * @param $errorMessage
     * @param $errorFile
     * @param $errorLine
     * @param array $errorContext
     * @throws IncompleteTaskException
     */
    abstract public function handleErrors($errorSeverity, $errorMessage, $errorFile, $errorLine, array $errorContext);
}