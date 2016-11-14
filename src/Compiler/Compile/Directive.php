<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Compile;

trait Directive
{
    /**
     * @var array
     */
    protected $directive = [];

    /**
     * @param $directive
     * @param $value
     * @return mixed
     */
    protected function callDirective($directive, $value)
    {
        return call_user_func($directive, trim(Expression::stripParentheses($value)));
    }

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
