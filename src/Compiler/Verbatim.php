<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler;

use View5\Template;

final class Verbatim
{
    /**
     * Placeholder to temporary mark the position of verbatim blocks.
     *
     * @var string
     */
    protected $verbatimPlaceholder = '@__verbatim__@';

    /**
     * Replace the raw placeholders with the original code stored in the raw blocks.
     *
     * @param Template $template
     * @return string
     */
    protected function restoreVerbatimBlocks(Template $template) : string
    {
        return preg_replace_callback('/'.preg_quote($this->verbatimPlaceholder).'/', function() use($template) {
            return array_shift($template['verbatimBlock']);
        }, $template->content());
    }

    /**
     * Store the verbatim blocks and replace them with a temporary placeholder.
     *
     * @param Template $template
     * @param  string  $value
     * @return string
     */
    protected function storeVerbatimBlocks(Template $template, string $value) : string
    {
        return preg_replace_callback('/(?<!@)@verbatim(.*?)@endverbatim/s', function ($matches) use($template) {
            $template['verbatimBlock'][] = $matches[1];

            return $this->verbatimPlaceholder;
        }, $value);
    }

    /**
     * @param Template $template
     * @param callable $next
     * @return Template
     */
    function __invoke(Template $template, callable $next) : Template
    {
        $value = $template->content();

        (strpos($value, '@verbatim') !== false) &&
            $template['content'] = $this->storeVerbatimBlocks($template, $value);

        /** @var Template $template */
        $template = $next($template);

        $template->verbatimBlock() &&
            $template['content'] = $this->restoreVerbatimBlocks($template);

        return $template;
    }
}
