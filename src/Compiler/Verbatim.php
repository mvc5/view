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
     * Get a placeholder to temporary mark the position of raw blocks.
     *
     * @param  int|string  $replace
     * @return string
     */
    protected function rawPlaceholder($replace)
    {
        return str_replace('#', $replace, '@__raw_block_#__@');
    }

    /**
     * Replace the raw placeholders with the original code stored in the raw blocks.
     *
     * @param Template $template
     * @return string
     */
    protected function restoreRawContent(Template $template) : string
    {
        return preg_replace_callback('/'.$this->rawPlaceholder('(\d+)').'/', function ($matches) use($template) {
            return $template['rawBlocks'][$matches[1]];
        }, $template->content());
    }

    /**
     * Store the verbatim blocks and replace them with a temporary placeholder.
     *
     * @param Template $template
     * @param  string  $value
     * @return string
     */
    protected function storePHPBlocks(Template $template, string $value) : string
    {
        return preg_replace_callback('/(?<!@)@php(.*?)@endphp/s', function ($matches) use($template) {
            return $this->storeRawBlock("<?php{$matches[1]}?>", $template);
        }, $value);
    }

    /**
     * Store a raw block and return a unique raw placeholder.
     *
     * @param  string  $value
     * @return string
     */
    protected function storeRawBlock($value, Template $template)
    {
        return $this->rawPlaceHolder(array_push($template['rawBlocks'], $value) - 1);
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
            return $this->storeRawBlock($matches[1], $template);
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
            $value = $this->storeVerbatimBlocks($template, $value);

        (strpos($template['content'], '@php') !== false) &&
            $value = $this->storePHPBlocks($template, $value);

        $template['content'] = $value;

        /** @var Template $template */
        $template = $next($template);

        $template->rawBlocks() &&
            $template['content'] = $this->restoreRawContent($template);

        return $template;
    }
}
