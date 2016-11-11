<?php
/**
 *
 */

namespace View5;

use Mvc5\Service;

class Factory
    implements Service, View
{
    /**
     *
     */
    use Engine\Engine;
    use Finder\Finder;
    use Template\Render;
    use Template\Section;

    /**
     * @param Engine\EngineResolver $engine
     * @param Finder\ViewFinder $finder
     * @param callable|null $provider
     * @param $model
     */
    function __construct(Engine\EngineResolver $engine, Finder\ViewFinder $finder, callable $provider = null, $model = null)
    {
        $this->engine = $engine;
        $this->finder = $finder;

        $model && $this->model = $model;
        $provider && $this->provider = $provider;
    }
}
