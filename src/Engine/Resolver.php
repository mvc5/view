<?php
/**
 *
 */

namespace View5\Engine;

use Mvc5\View\ViewEngine;

interface Resolver
{
    /**
     * @param string $path
     * @return ViewEngine
     */
    function resolve(string $path) : ViewEngine;
}
