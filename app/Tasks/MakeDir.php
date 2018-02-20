<?php
/**
 * MakeDir.php
 *
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
namespace Lazzier\Tasks;

use Lazzier\Contracts\TaskContract;
use Lazzier\Tasks\BaseTask;
use Lazzier\Traits\MakeThrowable;
use Lazzier\Exceptions\IncompleteTaskException;

/**
 * @executable
 */
class MakeDir extends BaseTask implements TaskContract
{
    use MakeThrowable;

    const MKDIR_FILE_EXISTS = 'mkdir(): File exists';
    const MKDIR_PERMISSION_DENIED = 'mkdir(): Permission denied';
    const MKDIR_NO_SUCH_FILE_OR_DIRECTORY = 'mkdir(): No such file or directory';

    /**
     * @var string
     * @required
     */
    protected $target;

    /**
     * @var int
     * @default(0755)
     */
    protected $mode;

    /**
     * @var bool
     * @default(true)
     */
    protected $recursive;

    protected $description = 'Make a directory at given target path.';

    public function __construct(string $target, int $mode = 0755, bool $recursive = true)
    {
        $this->target = $target;
        $this->mode = $mode;
        $this->recursive = $recursive;
    }

    public function invoke(): bool
    {
        $this->enableErrorHandler();
        $success = !!mkdir($this->target, $this->mode, $this->recursive);
        $this->disableErrorHandler();

        return $success;
    }

    public function params(): array
    {
        return [
            'target' => $this->target,
            'mode' => $this->mode,
            'recursive' => $this->recursive,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function handleErrors($errorSeverity, $errorMessage, $errorFile, $errorLine, array $errorContext)
    {
        switch ($errorMessage) {
            case self::MKDIR_FILE_EXISTS:
                throw new IncompleteTaskException('Directory already exists!', $errorSeverity);
            case self::MKDIR_PERMISSION_DENIED:
                throw new IncompleteTaskException('Insufficient permissions to create directory!', $errorSeverity);
            case self::MKDIR_NO_SUCH_FILE_OR_DIRECTORY:
                throw new IncompleteTaskException('Parent directory does not exists!', $errorSeverity);
            default:
                throw new IncompleteTaskException($errorMessage, $errorSeverity);
        }
    }
}
