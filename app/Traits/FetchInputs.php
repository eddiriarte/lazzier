<?php
/**
 * FetchInputss.php
 *
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
namespace Lazzier\Traits;

/**
 *
 */
trait FetchInputs
{
    protected $options;

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function artifact()
    {
        return $this->options['artifact'];
    }

    /**
     * @return null|string|string[]
     */
    public function release()
    {
        $index = strrpos($this->artifact(), '/');
        $file = (false === $index) ? $this->artifact() : substr($this->artifact(), $index + 1);
        $name = preg_replace('/([\w_\.\-]+)\.(tar|zip)(\.gz)?/', '$1', $file);

        return $name;
    }
}
