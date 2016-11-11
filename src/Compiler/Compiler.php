<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler;

interface Compiler
{
    /**
     * @param  string  $path
     * @return void
     */
    function compile($path);

    /**
     * @param array|null $directives
     * @return array
     */
    function directives(array $directives = null);

    /**
     * @param  string  $path
     * @return bool
     */
    function expired($path);

    /**
     * @param array|null $extensions
     * @return array
     */
    function extensions(array $extensions = null);

    /**
     * @param  string $path
     * @return string
     */
    function path($path);
}
