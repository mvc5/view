<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

trait Injections
{
    /**
     * @param string $expression
     * @return string
     */
    protected function compileAssign(string $expression) : string
    {
        list($variable, $value) = explode(',', $this->stripParentheses($expression), 2);

        return '<?php $' . trim($variable, '\'$') . ' = ' . $value . '; ?>';
    }

    /**
     * @param string $expression
     * @return string
     */
    protected function compileCall(string $expression) : string
    {
        $args = explode(',', $this->stripParentheses($expression));

        return '<?php echo $this->call(' . array_shift($args) . ', [' . implode(',', $args) . ']); ?>';
    }

    /**
     * @param string $expression
     * @return string
     */
    protected function compileInject(string $expression) : string
    {
        return $this->compilePlugin($expression);
    }

    /**
     * @param string $expression
     * @return string
     */
    protected function compilePlugin(string $expression) : string
    {
        return $this->service($expression, 'plugin');
    }

    /**
     * @param string $expression
     * @param $service
     * @return string
     */
    protected function service(string $expression, $service) : string
    {
        $args = explode(',', $this->stripParentheses($expression));

        $variable = array_shift($args);
        $plugin = array_shift($args) ?: $variable;

        list($variable, $plugin) = str_replace(['(', ')', '\\', '"', '\''], '', [$variable, $plugin]);

        return '<?php $' . $variable . ' = $this->' . $service . '(\'' . $plugin . '\', [\' . implode(\',\', $args) . \']); ?>';
    }

    /**
     * @param string $expression
     * @return string
     */
    protected function compileShared(string $expression) : string
    {
        return $this->service($expression, 'shared');
    }
}
