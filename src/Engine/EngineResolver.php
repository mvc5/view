<?php
/**
 *
 */

namespace View5\Engine;

class EngineResolver
    implements Resolver
{
    /**
     * @var string
     */
    protected $default;

    /**
     * @var \Mvc5\View\ViewEngine[]
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

        $this->default = array_pop($this->extensions);
    }

    /**
     * @param  string $name
     * @return \Mvc5\View\ViewEngine
     */
    protected function engine($name)
    {
        return isset($this->engine[$name]) ? $this->engine[$name] : null;
    }

    /**
     * @param  string $path
     * @return \Mvc5\View\ViewEngine
     */
    function resolve($path)
    {
        foreach($this->extensions as $extension => $name) {
            if (substr($path, -strlen('.' . $extension)) === '.' . $extension) {
                return $this->engine($name);
            }
        }

        return $this->engine($this->default);
    }
}
