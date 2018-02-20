<?php
/**
 * SystemPathContract.php
 *
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
namespace Lazzier\Contracts;

/**
 * Interface SystemPathContract
 * @package Lazzier\Contracts
 */
interface SystemPathContract
{
    const SEPARATOR = DIRECTORY_SEPARATOR;

    /**
     * Get the absolute path of the folder where the command was called.
     *
     * @return string Path to working directory
     */
    public function workDirectory(): string;

    /**
     * Get the absolute path to user's home directory.
     *
     * @return string Path to home directory
     */
    public function homeDirectory(): string;

    /**
     * Get the full path to a given relative path.
     *
     * @param  string $path Relative path
     * @return string       Full path
     */
    public function absolute(string $path): string;

    /**
     * Get the name of the file after last slash.
     *
     * @param  string $path Path to a file
     * @return string       Name of file
     */
    public function fileName(string $path): string;
}
