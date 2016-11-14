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
     * @var ViewEngine[]
     */
    protected $engine = [];

    /**
     * @var array
     */
    protected $extension = [
        'blade.phtml' => 'blade',
        'phtml'       => 'php'
    ];

    /**
     * @param array $engine
     * @param array|null $extension
     */
    function __construct(array $engine = [], array $extension = null)
    {
        $this->engine = $engine;

        $extension && $this->extension = $extension;

        $this->default = array_pop($this->extension);
    }

    /**
     * @param  string $name
     * @return ViewEngine
     */
    protected function engine($name)
    {
        return isset($this->engine[$name]) ? $this->engine[$name] : null;
    }

    /**
     * @param  string $path
     * @return ViewEngine
     */
    function resolve($path)
    {
        foreach($this->extension as $extension => $name) {
            if (substr($path, -strlen('.' . $extension)) === '.' . $extension) {
                return $this->engine($name);
            }
        }

        return $this->engine($this->default);
    }
}
