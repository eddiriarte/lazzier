<?php
/**
 * ArtisanDown.php
 *
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */

namespace Lazzier\Tasks;

use Lazzier\Contracts\TaskContract;
use Lazzier\Tasks\BaseTask;

/**
 *
 */
class ArtisanDown extends BaseTask implements TaskContract
{
    protected $description = 'Set current app into maintenance mode';

    protected $root;

    public function __construct(string $root)
    {
        $this->root = $root;
    }

    public function invoke(): bool
    {
        exec("php {$this->root}/artisan down", $output, $status);

        return 0 === $status;
    }

    public function params(): array
    {
        return [
            'root' => $this->root
        ];
    }
}
