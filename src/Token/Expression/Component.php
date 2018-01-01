<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

trait Component
{
    /**
     * Compile the component statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileComponent(string $expression) : string
    {
        return '<?php $__env->startComponent' . $expression . '; ?>';
    }

    /**
     * Compile the end-component statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndComponent() : string
    {
        return '<?php echo $__env->render(...$__env->endComponent()); ?>';
    }

    /**
     * Compile the slot statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileSlot(string $expression) : string
    {
        return '<?php $__env->startSlot' . $expression. '; ?>';
    }

    /**
     * Compile the end-slot statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndSlot() : string
    {
        return '<?php $__env->endSlot(); ?>';
    }
}
