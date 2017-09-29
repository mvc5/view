<?php
/**
 *
 */

namespace View5\Engine;

use Mvc5\Exception;
use Mvc5\View\ViewEngine;

class EngineResolver
    implements Resolver
{
    /**
     * @var string
     */
    protected $default = 'php';

    /**
     * @var ViewEngine[]
     */
    protected $engine = [];

    /**
     * @var array
     */
    protected $extensions = [
        'blade.phtml' => 'blade',
        'phtml'       => 'php'
    ];

    /**
     * @param array $engine
     * @param array $extensions
     */
    function __construct(array $engine = [], array $extensions = [])
    {
        $this->engine = $engine;

        $extensions && $this->extensions = $extensions;

        ($default = array_pop($this->extensions)) && $this->default =  $default;
    }

    /**
     * @param  string $name
     * @return ViewEngine
     * @throws \RuntimeException
     */
    protected function engine(string $name) : ViewEngine
    {
        return $this->engine[$name] ?? Exception::runtime('View engine not found: ' . $name);
    }

    /**
     * @param  string $path
     * @return ViewEngine
     */
    function resolve(string $path) : ViewEngine
    {
        foreach($this->extensions as $extension => $name) {
            if (substr($path, -strlen('.' . $extension)) === '.' . $extension) {
                return $this->engine($name);
            }
        }

        return $this->engine($this->default);
    }
}
