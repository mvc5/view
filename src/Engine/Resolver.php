<?php
/**
 *
 */

namespace View5\Engine;

use Mvc5\View\ViewEngine;

interface Resolver
{
    /**
     * @param $path
     * @return ViewEngine
     */
    function resolve($path);
}
