<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

use function array_shift;
use function implode;
use function trim;

trait Plugin
{
    /**
     * @param string $expression
     * @return string
     */
    protected function compileAssign(string $expression) : string
    {
        list($variable, $value) = $this->args($expression, 2);

        return '<?php $' . $this->var($variable) . ' = ' . $value . '; ?>';
    }

    /**
     * @param string $expression
     * @return string
     */
    protected function compileCall(string $expression) : string
    {
        return '<?php echo $this->call' . $expression . '; ?>';
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
     * @return string
     */
    protected function compileShared(string $expression) : string
    {
        return $this->service($expression, 'shared');
    }

    /**
     * Name of variable is required when service arguments are provided.
     *
     * @param string $expression
     * @param $service
     * @return string
     */
    protected function service(string $expression, $service) : string
    {
        $args = $this->args($expression);

        $variable = array_shift($args);
        $plugin = array_shift($args) ?: '\'' . trim($variable, '"\'$') . '\'';

        return '<?php $' . $this->var($variable) . ' = $this->' . $service . '(' . $plugin . ', [' . implode(',', $args) . ']); ?>';
    }
}
