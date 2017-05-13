<?php
/**
 *
 */

namespace View5\Engine;

interface Resolver
{
    /**
     * @param $path
     * @return \Mvc5\View\ViewEngine
     */
    function resolve($path);
}
