<?php
/**
 *
 */

namespace View5\Template;

use Mvc5\View\Template\Path;

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
        return $this->directory . DIRECTORY_SEPARATOR . $name . '.' . $extension;
    }

    /**
     * @param  string $name
     * @return string
     */
    function find(string $name) : string
    {
        return $this->path($name) ?? $this->paths[$name] = $this->match($name);
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function match(string $name) : string
    {
        return false !== strpos($name, '.') ? $name : (
            file_exists($file = $this->file($name, $this->extensions['blade'])) ? $file :
                $this->file($name, $this->extensions['php'])
        );
    }
}
