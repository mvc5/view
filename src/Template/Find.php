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
     * @var
     */
    protected $extension;

    /**
     * @var null|string
     */
    protected $extensions = ['blade.phtml', 'phtml'];

    /**
     * @param  string $name
     * @return string
     */
    function find($name)
    {
        return $this->path($name) ?: $this->path[$name] = $this->match($name);
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function match($name)
    {
        if (false !== strpos($name, '.')) {
            return $name;
        }

        foreach($this->extensions as $extension) {
            if (file_exists($file = $this->directory . DIRECTORY_SEPARATOR . $name . '.' . $extension)) {
                return $file;
            }
        }

        return $this->directory . DIRECTORY_SEPARATOR . $name . '.' . $this->extension;
    }
}
