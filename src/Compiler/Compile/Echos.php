<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Compile;

use View5\Compiler\Template;

final class Echos
{
    /**
     * @param  string  $value
     * @return string
     */
    protected function compileEchoDefaults(string $value) : string
    {
        return preg_replace('/^(?=\$)(.+?)(?:\s+or\s+)(.+?)$/s', 'isset($1) ? $1 : $2', $value);
    }

    /**
     * @param string $value
     * @param Template $template
     * @return string
     */
    protected function compileEscapedEchos(string $value, Template $template) : string
    {
        $pattern = sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', $template['escapedTag'][0], $template['escapedTag'][1]);

        $match = function($match) use($template) {
            $whitespace = empty($match[3]) ? '' : $match[3].$match[3];

            return $match[1] ? $match[0]
                : '<?php echo ' . $template->formatEcho($this->compileEchoDefaults($match[2])) . ' ?>' . $whitespace;
        };

        return preg_replace_callback($pattern, $match, $value);
    }

    /**
     * @param  string  $value
     * @param Template $template
     * @return string
     */
    protected function compileRawEchos(string $value, Template $template) : string
    {
        $pattern = sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', $template['rawTag'][0], $template['rawTag'][1]);

        $match = function ($match) {
            $whitespace = empty($match[3]) ? '' : $match[3] . $match[3];

            return $match[1] ? substr($match[0], 1) : '<?php echo ' . $this->compileEchoDefaults($match[2]) . '; ?>' . $whitespace;
        };

        return preg_replace_callback($pattern, $match, $value);
    }

    /**
     * @param string $value
     * @param Template $template
     * @return string
     */
    protected function compileRegularEchos(string $value, Template $template) : string
    {
        $pattern = sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', $template['contentTag'][0], $template['contentTag'][1]);

        $match = function ($match) use($template) {
            $whitespace = empty($match[3]) ? '' : $match[3] . $match[3];

            return $match[1] ? substr($match[0], 1)
                : '<?php echo ' . $template->formatEcho($this->compileEchoDefaults($match[2])) . '; ?>' . $whitespace;
        };

        return preg_replace_callback($pattern, $match, $value);
    }

    /**
     * @param Template $template
     * @return array
     */
    protected function echoMethods(Template $template) : array
    {
        $method = [
            'compileRawEchos'     => strlen(stripcslashes($template['rawTag'][0])),
            'compileEscapedEchos' => strlen(stripcslashes($template['escapedTag'][0])),
            'compileRegularEchos' => strlen(stripcslashes($template['contentTag'][0])),
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
     * @param Template $template
     * @param  string  $value
     * @return string
     */
    function __invoke(Template $template, string $value) : string
    {
        foreach($this->echoMethods($template) as $method => $length) {
            $value = $this->$method($value, $template);
        }

        return $value;
    }
}
