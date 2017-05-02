<?php
/**
 *
 */

namespace View5\Template;

use Mvc5\View\ViewEngine;
use View5\Engine\Resolver;

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
