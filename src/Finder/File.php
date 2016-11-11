<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Finder;

class File
    implements ViewFinder
{
    /**
     * @var array
     */
    protected $paths;

    /**
     * @var array
     */
    protected $templates = [];

    /**
     * @var array
     */
    protected $extensions = ['blade.phtml', 'phtml'];

    /**
     * @param array $paths
     * @param array $templates
     * @param array $extensions
     */
    function __construct(array $paths, array $templates = [], array $extensions = null)
    {
        $this->paths     = $paths;
        $this->templates = $templates;

        $extensions && $this->extensions = $extensions;
    }

    /**
     * @param  string $name
     * @return string
     */
    function find($name)
    {
        return isset($this->templates[$name]) ? $this->templates[$name] : (
            ($path = $this->findInPaths($name, $this->paths)) ? $this->templates[$name] = $path : null
        );
    }

    /**
     * @param  string $name
     * @param  array $paths
     * @return string
     */
    protected function findInPaths($name, $paths)
    {
        foreach($paths as $path) {
            foreach($this->extensions as $extension) {
                if (file_exists($file = $path . '/' . $name . '.' . $extension)) {
                    return $file;
                }
            }
        }

        return null;
    }
}
