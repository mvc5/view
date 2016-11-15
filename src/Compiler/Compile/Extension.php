<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Compile;

use View5\Compiler\Template;

trait Extension
{
    /**
     * Execute the user defined extensions.
     *
     * @param Template $template
     * @param  string  $value
     * @return string
     */
    protected function compileExtensions(Template $template, $value)
    {
        foreach($template->extensions() as $compiler) {
            $value = call_user_func($compiler, $value, $template);
        }

        return $value;
    }
}
