<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

use View5\Template;

trait Layouts
{
    /**
     * The name of the last section that was started.
     *
     * @var string
     */
    protected $lastSection;

    /**
     * Compile the append statements into valid PHP.
     *
     * @return string
     */
    protected function compileAppend() : string
    {
        return '<?php $__env->appendSection(); ?>';
    }

    /**
     * Compile the end-section statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndSection() : string
    {
        return '<?php $__env->stopSection(); ?>';
    }

    /**
     * Compile the extends statements into valid PHP.
     *
     * @param  string  $expression
     * @param Template $template
     * @return string
     */
    protected function compileExtends(string $expression, Template $template) : string
    {
        $expression = $this->stripParentheses($expression);

        $data = "<?php echo \$__env->render($expression, array_diff_key(get_defined_vars(), ['__template' => 1, '__child' => 1, 'this' => 1, '__ob_level__' => 1])); ?>";

        $template['footer'][] = $data;

        return '';
    }

    /**
     * Compile the overwrite statements into valid PHP.
     *
     * @return string
     */
    protected function compileOverwrite() : string
    {
        return '<?php $__env->stopSection(true); ?>';
    }

    /**
     * Replace the @parent directive to a placeholder.
     *
     * @return string
     */
    protected function compileParent()
    {
        return Template\Section\Stack::parentPlaceholder($this->lastSection ?: '');
    }

    /**
     * Compile the section statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileSection(string $expression) : string
    {
        $this->lastSection = trim($expression, "()'\" ");

        return "<?php \$__env->startSection{$expression}; ?>";
    }

    /**
     * Compile the show statements into valid PHP.
     *
     * @return string
     */
    protected function compileShow() : string
    {
        return '<?php echo $__env->yieldSection(); ?>';
    }

    /**
     * Compile the stop statements into valid PHP.
     *
     * @return string
     */
    protected function compileStop() : string
    {
        return '<?php $__env->stopSection(); ?>';
    }

    /**
     * Compile the yield statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileYield(string $expression) : string
    {
        return "<?php echo \$__env->yieldContent{$expression}; ?>";
    }
}
