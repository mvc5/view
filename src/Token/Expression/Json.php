<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

use View5\Template;

trait Json
{
    /**
     * @param string $expression
     * @param Template $template
     * @return string
     */
    protected function compileJson(string $expression, Template $template) : string
    {
        $parts = array_map('trim', explode(',', $this->stripParentheses($expression)));

        $depth = $parts[2] ?? 512;
        $options = $parts[1] ?? $template['json_options'];

        return '<?php echo json_encode(' . $parts[0] . ', ' . $options. ', ' . $depth . ') ?>';
    }
}
