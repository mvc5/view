<?php
/**
 *
 */

namespace View5;

use ArrayAccess;
use ArrayObject;
use Mvc5\Service\Service;
use Mvc5\ViewModel;
use Mvc5\View\View;

final class Render
    implements View
{
    /**
     *
     */
    use Template\Find;
    use Template\Template;
    use Template\Render;

    /**
     * @param Service $service
     * @param callable $engine
     * @param array $options
     */
    function __construct(Service $service, callable $engine, array $options = [])
    {
        $this->engine = $engine;
        $this->service = $service;

        $this->directory = $options['directory'] ?? null;
        $this->model = $options['model'] ?? ViewModel::class;
        $this->paths = (array) ($options['paths'] ?? null);

        $this->extensions += ($options['extensions'] ?? []);
    }
}
