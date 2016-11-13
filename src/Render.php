<?php
/**
 *
 */

namespace View5;

use Mvc5\Service;
use Mvc5\View\Template\Paths;

class Render
    implements Paths, Service, View
{
    /**
     *
     */
    use Engine\Engine;
    use Template\Find;
    use Template\Model;
    use Template\Render;

    /**
     * @param Engine\Resolver $resolver
     * @param callable|null $provider
     * @param array $options
     */
    function __construct(Engine\Resolver $resolver, callable $provider = null, array $options = [])
    {
        $this->resolver = $resolver;

        $provider && $this->provider = $provider;

        isset($options['directory'])
            && $this->directory = $options['directory'];

        isset($options['extensions'])
            && $this->extensions = $options['extensions'];

        isset($options['model'])
            && $this->model = $options['model'];

        isset($options['paths'])
            && $this->path = $options['paths'];

        $this->extension = array_pop($this->extensions);
    }
}
