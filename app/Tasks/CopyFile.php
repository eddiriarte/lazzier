<?php
namespace Lazzier\Tasks;

use Lazzier\Contracts\TaskContract;
use Lazzier\Traits\MakeThrowable;
use Lazzier\Exceptions\IncompleteTaskException;

/**
 * Class CopyFile
 * @package Lazzier\Tasks
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
class CopyFile extends BaseTask implements TaskContract
{
    const COPY_PERMISSION_DENIED = 'Permission denied';
    use MakeThrowable;

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

    protected $description = 'Copy a given source file to target.';

    public function __construct(string $source, string $target)
    {
        $this->source = $source;
        $this->target = $target;
    }

    public function invoke(): bool
    {
        $this->enableErrorHandler();
        $success = !!copy($this->source, $this->target);
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
        if (!!strpos($errorMessage, self::COPY_PERMISSION_DENIED)) {
            throw new IncompleteTaskException('Insufficient permissions to copy file!', $errorSeverity);
        } elseif (!!strpos($errorMessage, $this->source)) {
            throw new IncompleteTaskException('File to copy does not exists!', $errorSeverity);
        } elseif (!!strpos($errorMessage, $this->target)) {
            throw new IncompleteTaskException('Parent directory does not exists!', $errorSeverity);
        } else {
            throw new IncompleteTaskException($errorMessage, $errorSeverity);
        }
    }
}
