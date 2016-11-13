<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Path;

class FileFinder
    implements Finder
{
    /**
     * @var
     */
    protected $dir;

    /**
     * @var array
     */
    protected $directory = [];

    /**
     * @var
     */
    protected $ext;

    /**
     * @var array
     */
    protected $extensions = ['blade.phtml', 'phtml'];

    /**
     * @var array
     */
    protected $template = [];

    /**
     * @param array $directory
     * @param array $templates
     * @param array $extensions
     */
    function __construct(array $directory, array $templates = [], array $extensions = null)
    {
        $this->directory = $directory;
        $this->template  = $templates;

        $extensions && $this->extensions = $extensions;

        $this->dir = array_pop($directory);
        $this->ext = array_pop($this->extensions);
    }

    /**
     * @param  string $name
     * @return string
     */
    function find($name)
    {
        return isset($this->template[$name]) ? $this->template[$name] : $this->template[$name] = $this->path($name);
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function path($name)
    {
        if (false !== strpos($name, '.')) {
            return $name;
        }

        foreach($this->directory as $directory) {
            foreach($this->extensions as $extension) {
                if (file_exists($file = $directory . DIRECTORY_SEPARATOR . $name . '.' . $extension)) {
                    return $file;
                }
            }
        }

        return $this->dir . DIRECTORY_SEPARATOR . $name . '.' . $this->ext;
    }
}
