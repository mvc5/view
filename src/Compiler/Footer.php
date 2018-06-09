<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler;

use View5\Template;

use function array_reverse;
use function ltrim;
use function implode;

final class Footer
{
    /**
     * @param Template $template
     * @param callable $next
     * @return Template
     */
    function __invoke(Template $template, callable $next) : Template
    {
        /** @var Template $template */
        $template = $next($template);

        $template->footer() &&
            $template['content'] = ltrim($template->content(), PHP_EOL)
                . PHP_EOL . implode(PHP_EOL, array_reverse($template->footer()));

        return $template;
    }
}
