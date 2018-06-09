<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler;

use View5\Template;

use function is_array;
use function token_get_all;
use function trim;

final class Token
{
    /**
     * @param Template $template
     * @param  array|string  $token
     * @return string
     */
    protected function parse(Template $template, $token) : string
    {
        return !is_array($token) ? $token : (
            T_INLINE_HTML === $token[0] ? $this->token($template, $token[1]) : $token[1]
        );
    }

    /**
     * @param Template $template
     * @param string $content
     * @return string
     */
    protected function token(Template $template, string $content) : string
    {
        foreach($template->token() as $token) {
            $content = $token($template, $content);
        }

        return $content;
    }

    /**
     * @param Template $template
     * @param callable $next
     * @return Template
     */
    function __invoke(Template $template, callable $next) : Template
    {
        $result = '';

        foreach(token_get_all(trim($template->content())) as $token) {
            $result .= $this->parse($template, $token);
        }

        $template['content'] = $result;

        return $next($template);
    }
}
