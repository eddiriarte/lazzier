<?php
namespace Lazzier\Tasks;

use Lazzier\Contracts\TaskContract;

/**
 * Class RemoveDir
 * @package Lazzier\Tasks
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
class RemoveDir extends BaseTask implements TaskContract
{
    protected $description = 'Removes a directory from given path.';

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
        return !!rmdir($this->source);
    }

    public function params(): array
    {
        return [
            'source' => $this->source,
        ];
    }
}
