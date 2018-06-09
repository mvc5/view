<?php
/**
 *
 */

namespace View5\Template;

use Mvc5\View\Template\Path;

use function file_exists;
use function ltrim;
use function strpos;

trait Find
{
    /**
     *
     */
    use Path;

    /**
     * @var null|string
     */
    protected $directory;

    /**
     * @var array
     */
    protected $extensions = ['blade' => 'blade.php', 'php' => 'phtml'];

    /**
     * @param string $name
     * @param string $extension
     * @return string
     */
    protected function file(string $name, string $extension) : string
    {
        return $this->directory . DIRECTORY_SEPARATOR . ltrim($name, DIRECTORY_SEPARATOR) . '.' . $extension;
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function find(string $name) : string
    {
        return (string) $this->findPath($name);
    }

    /**
     * @param  string $name
     * @return string|FilePath
     */
    protected function findPath(string $name)
    {
        return $this->paths[$name] ?? $this->paths[$name] = $this->match($name);
    }

    /**
     * @param  string $name
     * @return string|FilePath
     */
    protected function match(string $name)
    {
        return false !== strpos($name, '.') ? $name : (
            file_exists($path = $this->file($name, $this->extensions['blade'])) ? new FilePath($path) :
                $this->file($name, $this->extensions['php'])
        );
    }
}
