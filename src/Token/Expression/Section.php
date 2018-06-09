<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

use View5\Template;

use function trim;

trait Section
{
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
        $template['footer'][] = $this->compileInclude($expression);
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
     * @param string $expression
     * @param Template $template
     * @return string
     */
    protected function compileParent(string $expression, Template $template) : string
    {
        return Template\Stack\Section::parentPlaceholder($template['lastSection'] ?: '');
    }

    /**
     * Compile the section statements into valid PHP.
     *
     * @param string $expression
     * @param Template $template
     * @return string
     */
    protected function compileSection(string $expression, Template $template) : string
    {
        $template['lastSection'] = trim($expression, '()\'" ');

        return '<?php $__env->startSection' . $expression . '; ?>';
    }

    /**
     * Compile the show statements into valid PHP.
     *
     * @return string
     */
    protected function compileShow() : string
    {
        return '<?php echo $__env->section(); ?>';
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
        return '<?php echo $__env->content' . $expression . '; ?>';
    }
}
