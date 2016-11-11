<?php
/**
 *
 */

namespace View5\Engine;

interface EngineResolver
{
    /**
     * @param $name
     * @return ViewEngine
     */
    function engine($name);

    /**
     * @param $path
     * @return ViewEngine
     */
    function resolve($path);
}
