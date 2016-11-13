<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Compile;

trait Echos
{
    /**
     * Array of opening and closing tags for regular echos.
     *
     * @var array
     */
    protected $contentTags = ['{{', '}}'];

    /**
     * The "regular" / legacy echo string format.
     *
     * @var string
     */
    protected $echoFormat = "htmlspecialchars(%s)";

    /**
     * Array of opening and closing tags for escaped echos.
     *
     * @var array
     */
    protected $escapedTags = ['{{{', '}}}'];

    /**
     * Array of opening and closing tags for raw echos.
     *
     * @var array
     */
    protected $rawTags = ['{!!', '!!}'];

    /**
     * @param  string  $value
     * @return string
     */
    protected function compileEchoDefaults($value)
    {
        return preg_replace('/^(?=\$)(.+?)(?:\s+or\s+)(.+?)$/s', 'isset($1) ? $1 : $2', $value);
    }

    /**
     * @param  string  $value
     * @return string
     */
    protected function compileEchos($value)
    {
        foreach ($this->echoMethods() as $method => $length) {
            $value = $this->$method($value);
        }

        return $value;
    }

    /**
     * @param  string  $value
     * @return string
     */
    protected function compileEscapedEchos($value)
    {
        $pattern = sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', $this->escapedTags[0], $this->escapedTags[1]);

        $match = function($match) {
            $whitespace = empty($match[3]) ? '' : $match[3].$match[3];

            return $match[1] ? $match[0]
                : '<?php echo ' . $this->formatEcho($this->compileEchoDefaults($match[2])) . ' ?>' . $whitespace;
        };

        return preg_replace_callback($pattern, $match, $value);
    }

    /**
     * @param  string  $value
     * @return string
     */
    protected function compileRawEchos($value)
    {
        $pattern = sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', $this->rawTags[0], $this->rawTags[1]);

        $match = function ($match) {
            $whitespace = empty($match[3]) ? '' : $match[3] . $match[3];

            return $match[1] ? substr($match[0], 1) : '<?php echo ' . $this->compileEchoDefaults($match[2]) . '; ?>' . $whitespace;
        };

        return preg_replace_callback($pattern, $match, $value);
    }

    /**
     * @param  string  $value
     * @return string
     */
    protected function compileRegularEchos($value)
    {
        $pattern = sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', $this->contentTags[0], $this->contentTags[1]);

        $match = function ($match) {
            $whitespace = empty($match[3]) ? '' : $match[3] . $match[3];

            return $match[1] ? substr($match[0], 1)
                : '<?php echo ' . $this->formatEcho($this->compileEchoDefaults($match[2])) . '; ?>' . $whitespace;
        };

        return preg_replace_callback($pattern, $match, $value);
    }

    /**
     * @return array
     */
    protected function echoMethods()
    {
        $method = [
            'compileRawEchos'     => strlen(stripcslashes($this->rawTags[0])),
            'compileEscapedEchos' => strlen(stripcslashes($this->escapedTags[0])),
            'compileRegularEchos' => strlen(stripcslashes($this->contentTags[0])),
        ];

        uksort($method, function ($method1, $method2) use ($method) {
            // Ensure the longest tags are processed first
            if ($method[$method1] > $method[$method2]) {
                return -1;
            }
            if ($method[$method1] < $method[$method2]) {
                return 1;
            }

            // Otherwise give preference to raw tags (assuming they've overridden)
            if ($method1 === 'compileRawEchos') {
                return -1;
            }
            if ($method2 === 'compileRawEchos') {
                return 1;
            }

            if ($method1 === 'compileEscapedEchos') {
                return -1;
            }
            if ($method2 === 'compileEscapedEchos') {
                return 1;
            }

            return 0;
        });

        return $method;
    }

    /**
     * @param $value
     * @return string
     */
    protected function formatEcho($value)
    {
        return sprintf($this->echoFormat, $value);
    }
}
