<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Engine;

class EngineResolver
    implements Resolver
{
    /**
     * @var ViewEngine[]
     */
    protected $engine = [];

    /**
     * @var array
     */
    protected $extensions = [
        'blade' => 'blade.phtml',
        'php'   => 'phtml'
    ];

    /**
     * @param array $engine
     * @param array|null $extensions
     */
    function __construct(array $engine = [], array $extensions = null)
    {
        $this->engine = $engine;

        $extensions && $this->extensions = $extensions;
    }

    /**
     * @param  string $name
     * @return ViewEngine
     */
    protected function engine($name)
    {
        return isset($this->engine[$name]) ? $this->engine[$name] : $this->error("Engine: $name not found");
    }

    /**
     * @param $message
     * @throws \InvalidArgumentException
     */
    protected function error($message)
    {
        throw new \InvalidArgumentException($message);
    }

    /**
     * @param  string $path
     * @return ViewEngine
     */
    function resolve($path)
    {
        foreach($this->extensions as $name => $extension) {
            if (substr($path, -strlen('.' . $extension)) === '.' . $extension) {
                return $this->engine($name);
            }
        }

        return $this->error("Unrecognized extension in file: $path");
    }
}
