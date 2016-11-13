<?php
/**
 *
 */

namespace View5\Engine;

trait Engine
{
    /**
     * @var Resolver
     */
    protected $resolver;

    /**
     * @param string $path
     * @return ViewEngine
     */
    protected function engine($path)
    {
        return $this->resolver->resolve($path);
    }
}
