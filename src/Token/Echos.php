<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token;

use View5\Template;

final class Echos
{
    /**
     * @param  string  $value
     * @return string
     */
    protected function defaults(string $value) : string
    {
        return preg_replace('/^(?=\$)(.+?)(?:\s+or\s+)(.+?)$/s', 'isset($1) ? $1 : $2', $value);
    }

    /**
     * @param string $value
     * @param array $match
     * @return string
     */
    protected function echo(string $value, array $match) : string
    {
        return '<?php echo ' . $value . ' ?>' . (empty($match[3]) ? '' : $match[3] . $match[3]);
    }

    /**
     * @param string $value
     * @param Template $template
     * @return string
     */
    protected function escaped(string $value, Template $template) : string
    {
        return $this->replace($value, $template['escapedTag'], function($match) use($template) {
            return $match[1] ? $match[0] : $this->echo($template->formatEcho($this->defaults($match[2])), $match);
        });
    }

    /**
     * @param  string  $value
     * @param Template $template
     * @return string
     */
    protected function raw(string $value, Template $template) : string
    {
        return $this->replace($value, $template['rawTag'], function($match) {
            return $match[1] ? substr($match[0], 1) : $this->echo($this->defaults($match[2]), $match);
        });
    }

    /**
     * @param string $value
     * @param Template $template
     * @return string
     */
    protected function regular(string $value, Template $template) : string
    {
        return $this->replace($value, $template['contentTag'], function($match) use($template) {
            return $match[1] ? substr($match[0], 1) : $this->echo($template->formatEcho($this->defaults($match[2])), $match);
        });
    }

    /**
     * @param string $value
     * @param array $tag
     * @param callable $match
     * @return string
     */
    protected function replace(string $value, array $tag, callable $match) : string
    {
        return preg_replace_callback(sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', $tag[0], $tag[1]), $match, $value);
    }

    /**
     * @param Template $template
     * @param  string  $value
     * @return string
     */
    function __invoke(Template $template, string $value) : string
    {
        return $this->regular($this->escaped($this->raw($value, $template), $template), $template);
    }
}
