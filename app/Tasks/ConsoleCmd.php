<?php
/**
 * ConsoleCmd.php
 *
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
namespace Lazzier\Tasks;

use Lazzier\Contracts\TaskContract;

/**
 *
 */
class ConsoleCmd extends BaseTask implements TaskContract
{
    protected $description = 'Execute given shell command.';

    /**
     * @var string
     * @required
     */
    protected $command;

    public function __construct(string $command)
    {
        $this->command = $command;
    }

    public function invoke(): bool
    {
        $command = escapeshellcmd($this->command);
        exec($command, $output, $return_var);

        return 0 === $return_var;
    }

    public function params(): array
    {
        return [
            'command' => $this->command
        ];
    }
}
