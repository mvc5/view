<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler;

interface Directives
{
    /**
     * @param array|null $directives
     * @return array
     */
    function directives(array $directives = null);
}
