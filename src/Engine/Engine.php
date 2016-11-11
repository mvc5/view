<?php
/**
 *
 */

namespace View5\Engine;

trait Engine
{
    /**
     * @var EngineResolver
     */
    protected $engine;

    /**
     * @param string $path
     * @return ViewEngine
     */
    protected function engine($path)
    {
        return $this->engine->resolve($path);
    }
}
