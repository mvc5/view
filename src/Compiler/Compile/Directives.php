<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Compile;

trait Directives
{
    /**
     * @var array
     */
    protected $directives = [];

    /**
     * @param  string  $name
     * @param  string|null  $value
     * @return string
     */
    protected function callDirective($name, $value)
    {
        '' !== $value && '(' === $value[0] && substr($value, -1) === ')'
            && $value = substr($value, 1, -1);

        return call_user_func($this->directives[$name], trim($value));
    }

    /**
     * @param array|null $directives
     * @return array
     */
    function directives(array $directives = null)
    {
        return null !== $directives ? $this->directives = $directives : $this->directives;
    }

    /**
     * @param $name
     * @return bool
     */
    protected function hasDirective($name)
    {
        return isset($this->directives[$name]);
    }
}
