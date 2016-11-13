<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler;

use Mvc5\Signal;

class Blade
    implements Compiler, Directives
{
    /**
     *
     */
    use Compile\Directive;
    use Compile\Echos;
    use Compile\Expression;
    use Compile\Verbatim;
    use Signal;

    /**
     * @var array
     */
    protected $compiler = ['Statements', 'Comments', 'Echos'];

    /**
     * @param array $options
     */
    function __construct(array $options = [])
    {
        isset($options['compiler']) &&
            $this->compiler = $options['compilers'] + $this->compiler;

        isset($options['contentTags']) &&
            $this->contentTags = $options['contentTags'];

        isset($options['directive']) &&
            $this->directive = $options['directive'];

        isset($options['echoFormat']) &&
            $this->echoFormat = $options['echoFormat'];

        isset($options['escapedTags']) &&
            $this->escapedTags = $options['escapedTags'];

        isset($options['rawTags']) &&
            $this->rawTags = $options['rawTags'];
    }

    /**
     * @param $directive
     * @param $value
     * @return mixed
     */
    protected function callDirective($directive, $value)
    {
        return $this->signal($directive, [trim($this->stripParentheses($value))]);
    }

    /**
     * @param string $value
     * @return string
     */
    function compile($value)
    {
        $result = '<?php /** @var \View5\View $__env */ ?>';

        (strpos($value, '@verbatim') !== false) &&
            $value = $this->storeVerbatimBlocks($value);

        $this->footer = [];

        foreach(token_get_all($value) as $token) {
            $result .= $this->parseToken($token);
        }

        $this->verbatimBlocks && $result = $this->restoreVerbatimBlocks($result);

        $this->footer && $result = ltrim($result, PHP_EOL) . PHP_EOL . implode(PHP_EOL, array_reverse($this->footer));

        return $result;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function compileComments($value)
    {
        $pattern = sprintf('/%s--(.*?)--%s/s', $this->contentTags[0], $this->contentTags[1]);

        return preg_replace($pattern, '', $value);
    }

    /**
     * Compile Blade statements that start with "@".
     *
     * @param string $value
     * @return mixed
     */
    protected function compileStatements($value)
    {
        $match = function ($match) {
            if (false !== strpos($match[1], '@')) {
                $match[0] = isset($match[3]) ? $match[1].$match[3] : $match[1];
            } elseif ($directive = $this->directive($match[1])) {
                $match[0] = $this->callDirective($directive, isset($match[3]) ? $match[3] : null);
            } elseif (method_exists($this, $method = 'compile'.ucfirst($match[1]))) {
                $match[0] = $this->$method(isset($match[3]) ? $match[3] : null);
            }

            return isset($match[3]) ? $match[0] : $match[0].$match[2];
        };

        return preg_replace_callback('/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', $match, $value);
    }

    /**
     * @param $content
     * @return mixed
     */
    protected function parseContent($content)
    {
        foreach($this->compiler as $type) {
            $content = $this->{"compile{$type}"}($content);
        }

        return $content;
    }

    /**
     * @param  array|string  $token
     * @return string
     */
    protected function parseToken($token)
    {
        return is_array($token) && T_INLINE_HTML === $token[0] ? $this->parseContent($token[1]) : $token;
    }
}
