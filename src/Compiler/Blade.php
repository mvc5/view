<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler;

class Blade
    implements Compiler
{
    /**
     *
     */
    use Compile\Directive;
    use Compile\Echos;
    use Compile\Expression;
    use Compile\Extension;
    use Compile\Verbatim;

    /**
     * @var array
     */
    protected $compiler = ['Extensions', 'Statements', 'Comments', 'Echos'];

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

        isset($options['extension']) &&
            $this->extension = $options['extension'];

        isset($options['rawTags']) &&
            $this->rawTags = $options['rawTags'];
    }

    /**
     * @param  string  $value
     * @return string
     */
    function compile($value)
    {
        $result = '<?php /** @var \View5\View $__env */ ?>';

        (strpos($value, '@verbatim') !== false) &&
            $value = $this->storeVerbatimBlocks($value);

        $this->footer = [];

        // Here we will loop through all of the tokens returned by the Zend lexer and
        // parse each one into the corresponding valid PHP. We will then have this
        // template as the correctly rendered PHP that can be rendered natively.
        foreach(token_get_all($value) as $token) {
            $result .= is_array($token) ? $this->parseToken($token) : $token;
        }

        $this->verbatimBlocks &&
            $result = $this->restoreVerbatimBlocks($result);

        // If there are any footer lines that need to get added to a template we will
        // add them here at the end of the template. This gets used mainly for the
        // template inheritance via the extends keyword that should be appended.
        (count($this->footer) > 0) &&
            $result = ltrim($result, PHP_EOL) . PHP_EOL . implode(PHP_EOL, array_reverse($this->footer));

        return $result;
    }

    /**
     * Compile Blade comments into valid PHP.
     *
     * @param  string  $value
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
     * @param  string  $value
     * @return mixed
     */
    protected function compileStatements($value)
    {
        $callback = function ($match) {
            if (false !== strpos($match[1], '@')) {
                $match[0] = isset($match[3]) ? $match[1].$match[3] : $match[1];
            } elseif ($this->hasDirective($match[1])) {
                $match[0] = $this->callDirective($match[1], isset($match[3]) ? $match[3] : null);
            } elseif (method_exists($this, $method = 'compile'.ucfirst($match[1]))) {
                $match[0] = $this->$method(isset($match[3]) ? $match[3] : null);
            }

            return isset($match[3]) ? $match[0] : $match[0].$match[2];
        };

        return preg_replace_callback('/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', $callback, $value);
    }

    /**
     * Parse the tokens from the template.
     *
     * @param  array  $token
     * @return string
     */
    protected function parseToken($token)
    {
        list($id, $content) = $token;

        if ($id == T_INLINE_HTML) {
            foreach($this->compiler as $type) {
                $content = $this->{"compile{$type}"}($content);
            }
        }

        return $content;
    }
}
