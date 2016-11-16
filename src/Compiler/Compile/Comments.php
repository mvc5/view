<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Compile;

use View5\Compiler\Template;

trait Comments
{
    /**
     * @param Template $template
     * @param string $value
     * @return string
     */
    protected function compileComments(Template $template, $value)
    {
        $pattern = sprintf('/%s--(.*?)--%s/s', $template['contentTag'][0], $template['contentTag'][1]);

        return preg_replace($pattern, '', $value);
    }
}
