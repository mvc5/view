<?php
/**
 *
 */

namespace View5\Template;

use Mvc5\View\ViewEngine;

trait Engine
{
    /**
     * @var \View5\Engine\Resolver
     */
    protected $resolver;

    /**
     * @param string $path
     * @return ViewEngine
     */
    protected function engine(string $path) : ViewEngine
    {
        return $this->resolver->resolve($path);
    }
}
