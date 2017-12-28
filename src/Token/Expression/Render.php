<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

trait Render
{
    /**
     * Compile the each statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileEach(string $expression) : string
    {
        return '<?php echo $__env->renderEach' . $expression . '; ?>';
    }

    /**
     * Compile the include statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileInclude(string $expression) : string
    {
        return $this->compileRender($expression);
    }

    /**
     * Compile the include-first statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileIncludeFirst($expression)
    {
        return $this->compileRender($expression, 'first');
    }

    /**
     * Compile the include-if statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileIncludeIf(string $expression) : string
    {
        return $this->compileRender($expression, 'if');
    }

    /**
     * Compile the include-when statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileIncludeWhen($expression)
    {
        return $this->compileRender($expression, 'when');
    }

    /**
     * Compile the render statements into valid PHP.
     *
     * @param string $expression
     * @param string $method
     * @return string
     */
    protected function compileRender(string $expression, string $method = '') : string
    {
        return '<?php echo $__env->render' . $method . '(' . $this->expr($expression) . ', $__env->vars(get_defined_vars())); ?>';
    }
}
