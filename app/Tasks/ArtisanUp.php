<?php
/**
 * ArtisanUp.php
 *
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */

namespace Lazzier\Tasks;

use Lazzier\Contracts\TaskContract;

/**
 *
 */
class ArtisanUp extends BaseTask implements TaskContract
{
    protected $description = 'Startup current app from maintenance mode';

    /**
     * @var string
     * @required
     */
    protected $root;

    public function __construct(string $root)
    {
        $this->root = $root;
    }

    public function invoke(): bool
    {
        exec("php {$this->root}/artisan up", $output, $status);

        return 0 === $status;
    }

    public function params(): array
    {
        return [
            'root' => $this->root
        ];
    }
}
