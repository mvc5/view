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
     * @param  string  $name
     * @param  string|null  $value
     * @return string
     */
    protected function callDirective($name, $value)
    {
        '' !== $value && '(' === $value[0] && substr($value, -1) === ')'
            && $value = substr($value, 1, -1);

        return call_user_func($this->directive[$name], trim($value));
    }

    /**
     * @param array|null $directives
     * @return array
     */
    function directives(array $directives = null)
    {
        return null !== $directives ? $this->directive = $directives : $this->directive;
    }

    /**
     * @param $name
     * @return bool
     */
    protected function hasDirective($name)
    {
        return isset($this->directive[$name]);
    }
}
