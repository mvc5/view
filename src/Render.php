<?php
/**
 *
 */

namespace View5;

use Mvc5\Service\Service;
use Mvc5\ViewModel;

class Render
    implements View
{
    /**
     *
     */
    use Template\Engine;
    use Template\Find;
    use Template\Template;
    use Template\Render;

    /**
     * @param Service $service
     * @param Engine\Resolver $resolver
     * @param array $options
     */
    function __construct(Service $service, Engine\Resolver $resolver, array $options = [])
    {
        $this->resolver = $resolver;
        $this->service = $service;

        $this->directory = $options['directory'] ?? null;
        $this->model = $options['model'] ?? ViewModel::class;
        $this->paths = $options['paths'] ?? [];
        $this->provider = $options['provider'] ?? null;

        isset($options['extensions'])
            && $this->extensions = $options['extensions'];

        $this->extension = array_pop($this->extensions);
    }
}
