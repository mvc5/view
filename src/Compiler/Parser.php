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
    use Compile\Comments;
    use Compile\Echos;
    use Compile\Expression;
    use Compile\Extension;
    use Compile\Statements;

    /**
     * @param Template $template
     * @param $content
     * @return mixed
     */
    protected function parseContent(Template $template, $content)
    {
        foreach($template->compiler() as $type) {
            $content = $this->{"compile{$type}"}($template, $content);
        }

        return $content;
    }

    /**
     * @param Template $template
     * @param  array|string  $token
     * @return string
     */
    protected function parseToken(Template $template, $token)
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
    function __invoke(Template $template, callable $next)
    {
        $result = '<?php /** @var \View5\View $__env */ ?>';

        foreach(token_get_all($template->content()) as $token) {
            $result .= $this->parseToken($template, $token);
        }

        $template['content'] = $result;

        return $next($template);
    }
}
