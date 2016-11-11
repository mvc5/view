<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Compile;

trait Extensions
{
    /**
     * @var array
     */
    protected $extensions = [];

    /**
     * @param  string  $value
     * @return string
     */
    protected function compileExtensions($value)
    {
        foreach($this->extensions as $compiler) {
            $value = call_user_func($compiler, $value, $this);
        }

        return $value;
    }

    /**
     * @param array|null $extensions
     * @return array
     */
    function extensions(array $extensions = null)
    {
        return null !== $extensions ? $this->extensions = $extensions : $this->extensions;
    }
}
