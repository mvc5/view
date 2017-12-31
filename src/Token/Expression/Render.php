<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

trait Render
{
    /**
     * @param string $expression
     * @return string
     */
    protected function compileEach(string $expression) : string
    {
        return $this->echoRender($expression, 'Each');
    }

    /**
     * @param string $expression
     * @return string
     */
    protected function compileInclude(string $expression) : string
    {
        return $this->echoRender($expression, 'Include', true);
    }

    /**
     * @param string $expression
     * @return string
     */
    protected function compileIncludeFirst($expression) : string
    {
        return $this->echoRender($expression, 'First', true);
    }

    /**
     * @param string $expression
     * @return string
     */
    protected function compileIncludeIf(string $expression) : string
    {
        return $this->echoRender($expression, 'If', true);
    }

    /**
     * @param string $expression
     * @return string
     */
    protected function compileIncludeWhen($expression) : string
    {
        return $this->echoRender($expression, 'When', true);
    }

    /**
     * @param string $expression
     * @param string $method
     * @return string
     */
    protected function compileRender(string $expression) : string
    {
        return $this->echoRender($expression);
    }

    /**
     * @param string $expression
     * @return string
     */
    protected function compileRenderFirst($expression) : string
    {
        return $this->echoRender($expression, 'First');
    }

    /**
     * @param string $expression
     * @return string
     */
    protected function compileRenderIf(string $expression) : string
    {
        return $this->echoRender($expression, 'If');
    }

    /**
     * @param string $expression
     * @return string
     */
    protected function compileRenderWhen($expression) : string
    {
        return $this->echoRender($expression, 'When');
    }

    /**
     * @param string $expression
     * @param string|null $method
     * @param bool|false $merge
     * @return string
     */
    protected function echoRender(string $expression, string $method = null, bool $merge = false) : string
    {
        return '<?php echo $__env->render' . $method . '(' .
            $this->expr($expression) . ($merge ? ', $__env->vars(get_defined_vars())' : '')
        . '); ?>';
    }
}
