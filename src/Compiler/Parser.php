<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler;

class Parser
{
    /**
     *
     */
    use Compile\Expression;

    /**
     * @param Template $template
     * @param string $content
     * @return string
     */
    protected function parseContent(Template $template, string $content) : string
    {
        foreach($template->compiler() as $type) {
            $content = $type($template, $content);
        }

        return $content;
    }

    /**
     * @param Template $template
     * @param  array|string  $token
     * @return string
     */
    protected function parseToken(Template $template, $token) : string
    {
        return !is_array($token) ? $token : (
            T_INLINE_HTML === $token[0] ? $this->parseContent($template, $token[1]) : $token[1]
        );
    }

    /**
     * @param Template $template
     * @param callable $next
     * @return Template
     */
    function __invoke(Template $template, callable $next) : Template
    {
        $result = '<?php /** @var \View5\View $__env */ ?>';

        foreach($template->import() as $namespace) {
            $result .= $this->import($namespace);
        }

        foreach(token_get_all(trim($template->content())) as $token) {
            $result .= $this->parseToken($template, $token);
        }

        $template['content'] = $result;

        return $next($template);
    }
}
