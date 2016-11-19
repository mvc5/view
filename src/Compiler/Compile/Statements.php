<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Compile;

use View5\Compiler\Template;

trait Statements
{
    /**
     * @param $directive
     * @param $value
     * @return mixed
     */
    protected function callDirective($directive, $value)
    {
        return call_user_func($directive, trim(Expression::stripParentheses($value)));
    }

    /**
     * Compile Blade statements that start with "@".
     *
     * @param Template $template
     * @param string $value
     * @return mixed
     */
    protected function compileStatements(Template $template, $value)
    {
        $match = function ($match) use($template) {
            if (false !== strpos($match[1], '@')) {
                $match[0] = isset($match[3]) ? $match[1].$match[3] : $match[1];
            } elseif ($directive = $template->directive($match[1])) {
                $match[0] = $this->callDirective($directive, isset($match[3]) ? $match[3] : null);
            } elseif (method_exists($this, $method = 'compile' . $match[1])) {
                $match[0] = $this->$method(isset($match[3]) ? $match[3] : null, $template);
            }

            return isset($match[3]) ? $match[0] : $match[0].$match[2];
        };

        return preg_replace_callback('/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', $match, $value);
    }
}
