<?php
/**
 *
 */

namespace View5\Engine;

interface Resolver
{
    /**
     * @param $path
     * @return ViewEngine
     */
    function resolve($path);
}
