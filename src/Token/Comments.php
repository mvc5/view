<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token;

use View5\Template;

use function preg_replace;
use function sprintf;

final class Comments
{
    /**
     * @param Template $template
     * @param string $value
     * @return string
     */
    function __invoke(Template $template, string $value) : string
    {
        return preg_replace(
            sprintf('/%s--(.*?)--%s/s', $template['contentTag'][0], $template['contentTag'][1]), '', $value
        );
    }
}
