<?php
/**
 * Created by PhpStorm.
 * User: eiriarte
 * Date: 30.01.18
 * Time: 12:15
 */

namespace Lazzier\Contracts;


/**
 * Class BaseCommand
 * @package Lazzier\Commands
 */
interface CommandContract
{
    public function select(string $message, array $choices, bool $allowMultiple = true): array;

    /**
     * Write a string in an info box.
     *
     * @param  string|array $messages
     * @return void
     */
    public function infoBox($messages);

    /**
     * Confirm a question with the user.
     *
     * @param  string $question
     * @param  bool $default
     * @return bool
     */
    public function confirm($question, $default = false);

    /**
     * Prompt the user for input.
     *
     * @param  string $question
     * @param  string $default
     * @return string
     */
    public function ask($question, $default = null);

    /**
     * Prompt the user for input with auto completion.
     *
     * @param  string $question
     * @param  array $choices
     * @param  string $default
     * @return string
     */
    public function anticipate($question, array $choices, $default = null);

    /**
     * Prompt the user for input but hide the answer from the console.
     *
     * @param  string $question
     * @param  bool $fallback
     * @return string
     */
    public function secret($question, $fallback = true);

    /**
     * Give the user a single choice from an array of answers.
     *
     * @param  string $question
     * @param  array $choices
     * @param  string $default
     * @param  mixed $attempts
     * @param  bool $multiple
     * @return string
     */
    public function choice($question, array $choices, $default = null, $attempts = null, $multiple = null);

    /**
     * Format input to textual table.
     *
     * @param  array $headers
     * @param  \Illuminate\Contracts\Support\Arrayable|array $rows
     * @param  string $tableStyle
     * @param  array $columnStyles
     * @return void
     */
    public function table($headers, $rows, $tableStyle = 'default', array $columnStyles = []);

    /**
     * Write a string as standard output.
     *
     * @param  string $string
     * @param  string $style
     * @param  null|int|string $verbosity
     * @return void
     */
    public function line($string, $style = null, $verbosity = null);

    /**
     * Write a string as comment output.
     *
     * @param  string $string
     * @param  null|int|string $verbosity
     * @return void
     */
    public function comment($string, $verbosity = null);

    /**
     * Write a string as question output.
     *
     * @param  string $string
     * @param  null|int|string $verbosity
     * @return void
     */
    public function question($string, $verbosity = null);

    /**
     * Write a string as error output.
     *
     * @param  string $string
     * @param  null|int|string $verbosity
     * @return void
     */
    public function error($string, $verbosity = null);

    /**
     * Write a string in an alert box.
     *
     * @param  string $string
     * @return void
     */
    public function alert($string);

    /**
     * Gets the concrete implementation of the notifier. Then
     * creates a new notification and send it through the notifier.
     *
     * @param string $title
     * @param string $body
     * @param string|null $icon
     */
    public function notify(string $title, string $body, $icon = null): void;
}