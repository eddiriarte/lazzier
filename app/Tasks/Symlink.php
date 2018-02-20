<?php
namespace Lazzier\Tasks;

use Lazzier\Exceptions\IncompleteTaskException;
use Lazzier\Traits\MakeThrowable;

/**
 * Task: Symlink
 *
 * @package Lazzier\Tasks
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
class Symlink extends BaseTask
{
    use MakeThrowable;

    const SYMLINK_FILE_EXISTS = 'symlink(): File exists';

    const SYMLINK_PERMISSION_DENIED = 'symlink(): Permission denied';

    const SYMLINK_NO_SUCH_FILE_OR_DIRECTORY = 'symlink(): No such file or directory';

    /**
     * @var string
     * @required
     */
    protected $source;

    /**
     * @var string
     * @required
     */
    protected $target;

    /**
     * Task description
     */
    protected $description = 'Add a soft link to given source at predefined target.';

    public function __construct(string $source, string $target)
    {
        $this->source = $source;
        $this->target = $target;
    }

    public function invoke(): bool
    {
        $this->enableErrorHandler();
        $success = !!symlink($this->source, $this->target);
        $this->disableErrorHandler();

        return $success;
    }

    public function params(): array
    {
        return [
            'source' => $this->source,
            'target' => $this->target,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function handleErrors($errorSeverity, $errorMessage, $errorFile, $errorLine, array $errorContext)
    {
        switch ($errorMessage) {
            case self::SYMLINK_FILE_EXISTS:
                throw new IncompleteTaskException('Symlink already exists!', $errorSeverity);
            case self::SYMLINK_PERMISSION_DENIED:
                throw new IncompleteTaskException('Insufficient permisions to create symlink!', $errorSeverity);
            case self::SYMLINK_NO_SUCH_FILE_OR_DIRECTORY:
                throw new IncompleteTaskException('Parent directory does not exists!', $errorSeverity);
            default:
                throw new IncompleteTaskException($errorMessage, $errorSeverity);
        }
    }
}
