<?php
/**
 *
 */

namespace View5;

use Mvc5\Service;

class Render
    implements Service, View
{
    /**
     *
     */
    use Engine\Engine;
    use Path\Find;
    use Template\Model;
    use Template\Render;

    /**
     * @param Engine\Resolver $resolver
     * @param Path\Finder $finder
     * @param callable|null $provider
     * @param $model
     */
    function __construct(Engine\Resolver $resolver, Path\Finder $finder, callable $provider = null, $model = null)
    {
        $this->resolver = $resolver;
        $this->finder = $finder;

        $model && $this->model = $model;
        $provider && $this->provider = $provider;
    }
}
