<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token;

use View5\Template;

use function array_map;
use function explode;
use function is_string;
use function method_exists;
use function preg_replace_callback;
use function str_replace;
use function strpos;
use function substr;
use function trim;

class Expression
{
    /**
     *
     */
    use Expression\Component;
    use Expression\Conditional;
    use Expression\Import;
    use Expression\Json;
    use Expression\Loop;
    use Expression\Plugin;
    use Expression\Push;
    use Expression\RawPHP;
    use Expression\Render;
    use Expression\Section;

    /**
     * @param string $expression
     * @param int|null $limit
     * @return array
     */
    static function args(string $expression , int $limit = null) : array
    {
        return array_map('trim', explode(',', static::expr($expression), $limit ?? \PHP_INT_MAX));
    }

    /**
     * @param Template $template
     * @param string $expression
     * @param $directive
     * @return string
     */
    protected function create(Template $template, string $expression, $directive) : string
    {
        return is_string($directive) ? str_replace(
            ['{expression}', '{expr}', '{var}'], [$expression, $this->expr($expression), $this->var($expression)], $directive
        ) : $directive($expression, $template);
    }

    /**
     * Strip the parentheses from the given expression.
     *
     * @param string $expression
     * @return string
     */
    static function expr(string $expression) : string
    {
        '' !== $expression && '(' === $expression[0]
            && $expression = substr($expression, 1, -1);

        return trim($expression);
    }

    /**
     * @param string $expression
     * @return string
     */
    static function var(string $expression) : string
    {
        return trim($expression, '()"\'$ ');
    }

    /**
     * Compile Blade statements that start with "@".
     *
     * @param Template $template
     * @param string $value
     * @return string
     */
    function __invoke(Template $template, string $value) : string
    {
        $match = function ($match) use($template) {
            if (false !== strpos($match[1], '@')) {
                $match[0] = isset($match[3]) ? $match[1] . $match[3] : $match[1];
            } elseif ($directive = $template->directive($match[1])) {
                $match[0] = $this->create($template, $match[3] ?? '', $directive);
            } elseif (method_exists($this, $method = 'compile' . $match[1])) {
                $match[0] = $this->$method($match[3] ?? '', $template);
            }

            return isset($match[3]) ? $match[0] : $match[0] . $match[2];
        };

        return preg_replace_callback('/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', $match, $value);
    }
}
