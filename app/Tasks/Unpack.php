<?php
namespace Lazzier\Tasks;

use Lazzier\Contracts\TaskContract;

/**
 * Class Unpack
 * @package Lazzier\Tasks
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
class Unpack extends BaseTask implements TaskContract
{
    protected $description = 'Unpack given resource to target folder';

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
     * @var string
     * @default('tar+gz')
     */
    protected $format;

    public function __construct(string $source, string $target, string $format = 'tar+gz')
    {
        $this->source = $source;
        $this->target = $target;
        $this->format = $format;
    }

    public function params(): array
    {
        return [
            'source' => $this->source,
            'target' => $this->target,
            'format' => $this->format,
        ];
    }

    public function invoke(): bool
    {
        mkdir($this->target, 0755, true);
        exec("tar -xzvf {$this->source} -C {$this->target}", $output, $status);

        return 0 === $status;
    }
}
