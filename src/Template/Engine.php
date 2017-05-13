<?php
/**
 *
 */

namespace View5\Template;

trait Engine
{
    /**
     * @var \View5\Engine\Resolver
     */
    protected $resolver;

    /**
     * @param string $path
     * @return \Mvc5\View\ViewEngine
     */
    protected function engine($path)
    {
        return $this->resolver->resolve($path);
    }
}
