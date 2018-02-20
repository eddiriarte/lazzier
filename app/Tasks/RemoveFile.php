<?php
/**
 * RemoveFile.php
 *
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
namespace Lazzier\Tasks;

use Lazzier\Contracts\TaskContract;

/**
 *
 */
class RemoveFile extends BaseTask implements TaskContract
{
    protected $description = 'Removes a file from given path.';

    /**
     * @var string
     * @required
     */
    protected $source;

    public function __construct(string $source)
    {
        $this->source = $source;
    }

    public function invoke(): bool
    {
        return !!unlink($this->source);
    }

    public function params(): array
    {
        return [
            'source' => $this->source,
        ];
    }
}
