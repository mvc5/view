<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Compile;

use View5\Compiler\Template;

final class Extension
{
    /**
     * Execute the user defined extensions.
     *
     * @param Template $template
     * @param  string  $value
     * @return string
     */
    function __invoke(Template $template, string $value) : string
    {
        foreach($template->extension() as $extension) {
            $value = $extension($value, $template);
        }

        return $value;
    }
}
