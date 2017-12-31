<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

use View5\Template;
use View5\Token\Expression;

trait Conditional
{
    /**
     * Compile the case statements into valid PHP.
     *
     * @param string $expression
     * @param Template $template
     * @return string
     */
    protected function compileCase(string $expression, Template $template) : string
    {
        if ($template['firstCaseInSwitch']) {
            $template['firstCaseInSwitch'] = false;

            return 'case ' . $expression. ': ?>';
        }

        return '<?php case ' . $expression . ': ?>';
    }

    /**
     * Compile the default statements in switch case into valid PHP.
     *
     * @return string
     */
    protected function compileDefault() : string
    {
        return '<?php default: ?>';
    }

    /**
     * Compile the else statements into valid PHP.
     *
     * @return string
     */
    protected function compileElse() : string
    {
        return '<?php else: ?>';
    }

    /**
     * Compile the else-if statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileElseIf(string $expression) : string
    {
        return '<?php elseif' . $expression . ': ?>';
    }

    /**
     * Compile the end-isset statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndIsset() : string
    {
        return '<?php endif; ?>';
    }

    /**
     * Compile the end-if statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndIf() : string
    {
        return '<?php endif; ?>';
    }

    /**
     * Compile the end switch statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndSwitch() : string
    {
        return '<?php endswitch; ?>';
    }

    /**
     * Compile the end-unless statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndUnless() : string
    {
        return '<?php endif; ?>';
    }

    /**
     * Compile the has section statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileHasSection(string $expression) : string
    {
        return '<?php if (!empty(trim($__env->content' . $expression . '))): ?>';
    }

    /**
     * Compile the if statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileIf(string $expression) : string
    {
        return '<?php if' . $expression . ': ?>';
    }

    /**
     * Compile the if-isset statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileIsset(string $expression) : string
    {
        return '<?php if(isset'. $expression . '): ?>';
    }

    /**
     * Compile the switch statements into valid PHP.
     *
     * @param string $expression
     * @param Template $template
     * @return string
     */
    protected function compileSwitch(string $expression, Template $template) : string
    {
        $template['firstCaseInSwitch'] = true;

        return '<?php switch' . $expression . ':';
    }

    /**
     * Compile the unless statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileUnless(string $expression) : string
    {
        return '<?php if (!' . $expression . '): ?>';
    }
}
