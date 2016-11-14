<?php
/**
 *
 */

namespace View5\Compiler;

trait Directive
{
    /**
     * @var array
     */
    protected $directive = [];

    /**
     * @param $name
     * @return callable
     */
    protected function directive($name)
    {
        return isset($this->directive[$name]) ? $this->directive[$name] : null;
    }

    /**
     * @param array|null $directives
     * @return array
     */
    function directives(array $directives = null)
    {
        return null !== $directives ? $this->directive = $directives : $this->directive;
    }
}
