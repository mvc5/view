<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

trait Push
{
    /**
     * Compile the end-prepend statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndPrepend() : string
    {
        return '<?php $__env->stopPrepend(); ?>';
    }

    /**
     * Compile the end-push statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndPush() : string
    {
        return '<?php $__env->stopPush(); ?>';
    }

    /**
     * Compile the prepend statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compilePrepend(string $expression) : string
    {
        return '<?php $__env->startPrepend' . $expression . '; ?>';
    }

    /**
     * Compile the push statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compilePush(string $expression) : string
    {
        return '<?php $__env->startPush' . $expression . '; ?>';
    }

    /**
     * Compile the stack statements into the content.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileStack(string $expression) : string
    {
        return '<?php echo $__env->stack' . $expression . '; ?>';
    }
}
