<?php
/**
 *
 */

namespace View5\Compiler;

interface Compiler
{
    /**
     * @param  string  $value
     * @return string
     */
    function compile($value);
}
