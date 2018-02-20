<?php
/**
 * FetchYaml.php
 *
 * @author: Eduardo Iriarte <eiriarte@3pc.de>
 */
namespace Lazzier\Traits;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Lazzier\Facades\Path;
use Symfony\Component\Yaml\Yaml;

trait FetchYaml
{
    protected $config;

    /**
     * @return mixed
     * @throws FileNotFoundException
     */
    public function getConfig()
    {
        if (!empty($this->config)) {
            return $this->config;
        }

        throw new FileNotFoundException("Configuration must be loaded first", 1);
    }

    /**
     * @param string $file
     * @return $this
     * @throws FileNotFoundException
     */
    public function load(string $file)
    {
        $path = Path::absolute($file);
        if (file_exists($path)) {
            $this->config = Yaml::parse(file_get_contents($path));

            return $this;
        }

        throw new FileNotFoundException("Unable to load release config from: $path");
    }

    /**
     * Get string value of a given key from configuration
     *
     * @param string $key
     * @param string|null $context
     * @return string
     */
    public function getString(string $key, string $context = null): string
    {
        return $this->fetch($key, '', $context);
    }

    /**
     * Get list values from given key in configuration
     *
     * @param string $key
     * @param string|null $context
     * @return array
     */
    public function getArray(string $key, string $context = null): array
    {
        return $this->fetch($key, [], $context);
    }

    private function fetch(string $key, $default, $context = null)
    {
        $scope  = !empty($context) ? $this->getConfig()[$context] : $this->getConfig();

        return isset($scope[$key]) ? $scope[$key] : $default;
    }
}
