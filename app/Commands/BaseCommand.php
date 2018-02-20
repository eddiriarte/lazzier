<?php

namespace Lazzier\Commands;

use EddIriarte\Console\Helpers\SelectionHelper;
use EddIriarte\Console\Inputs\CheckboxInput;
use EddIriarte\Console\Inputs\RadioInput;
use LaravelZero\Framework\Commands\Command;
use Lazzier\Contracts\CommandContract;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BaseCommand
 * @package Lazzier\Commands
 */
abstract class BaseCommand extends Command implements CommandContract
{
    /**
     * Styling ASCII-Art as command title.
     *
     * @var array
     */
    protected $title = [
        "   ___                                                   ",
        "  /\_ \                             __                   ",
        "  \//\ \      __     ____    ____  /\_\     __   _ __    ",
        "    \ \ \   /'__`\  /\_ ,`\ /\_ ,`\\\/\ \  /'__`\/\`'__\  ",
        "     \_\ \_/\ \L\.\_\/_/  /_\/_/  /_\ \ \/\  __/\ \ \/   ",
        "     /\____\ \__/.\_\ /\____\ /\____\\\ \_\ \____\\\ \_\   ",
        "     \/____/\/__/\/_/ \/____/ \/____/ \/_/\/____/ \/_/   ",
        "                                                         ",
    ];

    /**
     * Prints formatted headline to standard output.
     *
     * @param  array|string  $input lines or single line to be printed
     */
    public function headline($input)
    {
        if (!$this->output->getFormatter()->hasStyle('headline')) {
            $style = new OutputFormatterStyle('blue');
            $this->output->getFormatter()->setStyle('headline', $style);
        }

        $lines = is_array($input) ? $input : [$input];
        foreach ($lines as $line) {
            $this->output->writeLn("<headline>$line</>");
        }
    }

    public function prepare()
    {
        $this->headline($this->title);
        $this->headline(str_repeat(' ', 36) . 'lazzier-cli v' . config('app.version'));
        $this->comment('');
        $this->infoBox([
            strtoupper($this->name),
            $this->description,
        ]);

        $this->getHelperSet()->set(new SelectionHelper($this->input, $this->output));
    }

    /**
     * Execute the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $state = $this->laravel->call([$this, 'prepare']);

        return parent::execute($input, $output);
    }

    public function select(string $message, array $choices, bool $allowMultiple = true): array
    {
        $helper = $this->getHelper('selection');
        $question = $allowMultiple ? new CheckboxInput($message, $choices) : new RadioInput($message, $choices);

        return $helper->select($question);
    }

    /**
     * Write a string in an info box.
     *
     * @param  string|array  $messages
     * @return void
     */
    public function infoBox($messages)
    {
        $maxLenght = 0;
        if (is_array($messages)) {
            $maxLenght = array_reduce($messages, function ($max, $msg) {
                return max($max, strlen($msg));
            }, 0);
        } elseif (is_string($messages)) {
            $maxLenght = strlen($messages);
            $messages = [$messages];
        }

        $this->comment(str_repeat('*', $maxLenght + 12));
        foreach ($messages as $string) {
            $this->comment('*     ' . $string);
        }
        $this->comment(str_repeat('*', $maxLenght + 12));

        $this->output->writeln('');
    }
}
