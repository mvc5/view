<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Compile;

trait Extension
{
    /**
     * @var array
     */
    protected $extension = [];

    /**
     * Execute the user defined extensions.
     *
     * @param  string  $value
     * @return string
     */
    protected function compileExtensions($value)
    {
        foreach ($this->extension as $compiler) {
            $value = call_user_func($compiler, $value, $this);
        }

        return $value;
    }

    /**
     * @param $name
     * @return callable
     */
    protected function extension($name)
    {
        return isset($this->extension[$name]) ? $this->extension[$name] : null;
    }

    /**
     * @param array|null $extensions
     * @return array
     */
    function extensions(array $extensions = null)
    {
        return null !== $extensions ? $this->extension = $extensions : $this->extension;
    }
}
