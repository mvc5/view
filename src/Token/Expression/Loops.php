<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

use View5\Template;

trait Loops
{
    /**
     * Compile the break statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileBreak($expression)
    {
        if ($expression) {
            preg_match('/\(\s*(-?\d+)\s*\)$/', $expression, $matches);

            return $matches ? '<?php break '.max(1, $matches[1]).'; ?>' : "<?php if{$expression} break; ?>";
        }

        return '<?php break; ?>';
    }

    /**
     * Compile the continue statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileContinue($expression)
    {
        if ($expression) {
            preg_match('/\(\s*(-?\d+)\s*\)$/', $expression, $matches);

            return $matches ? '<?php continue '.max(1, $matches[1]).'; ?>' : "<?php if{$expression} continue; ?>";
        }

        return '<?php continue; ?>';
    }

    /**
     * Compile the for-else-empty and empty statements into valid PHP.
     *
     * @param  string  $expression
     * @param Template $template
     * @return string
     */
    protected function compileEmpty($expression, Template $template)
    {
        if ($expression) {
            return "<?php if(empty{$expression}): ?>";
        }

        $empty = '$__empty_'.$template['forElseCounter']--;

        return "<?php endforeach; \$__env->popLoop(); \$loop = \$__env->getLastLoop(); if ({$empty}): ?>";
    }

    /**
     * Compile the end-empty statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndEmpty()
    {
        return '<?php endif; ?>';
    }

    /**
     * Compile the end-for statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndFor()
    {
        return '<?php endfor; ?>';
    }

    /**
     * Compile the end-for-each statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndForEach()
    {
        return '<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>';
    }

    /**
     * Compile the end-for-else statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndForElse()
    {
        return '<?php endif; ?>';
    }

    /**
     * Compile the end-while statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndWhile()
    {
        return '<?php endwhile; ?>';
    }

    /**
     * Compile the for statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileFor($expression)
    {
        return "<?php for{$expression}: ?>";
    }

    /**
     * Compile the for-each statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileForEach($expression)
    {
        preg_match('/\( *(.*) +as *(.*)\)$/is', $expression, $matches);

        $iteratee = trim($matches[1]);

        $iteration = trim($matches[2]);

        $initLoop = "\$__currentLoopData = {$iteratee}; \$__env->addLoop(\$__currentLoopData);";

        $iterateLoop = '$__env->incrementLoopIndices(); $loop = $__env->getLastLoop();';

        return "<?php {$initLoop} foreach(\$__currentLoopData as {$iteration}): {$iterateLoop} ?>";
    }

    /**
     * Compile the for-else statements into valid PHP.
     *
     * @param  string  $expression
     * @param Template $template
     * @return string
     */
    protected function compileForElse($expression, Template $template)
    {
        $empty = '$__empty_'.++$template['forElseCounter'];

        preg_match('/\( *(.*) +as *(.*)\)$/is', $expression, $matches);

        $iteratee = trim($matches[1]);

        $iteration = trim($matches[2]);

        $initLoop = "\$__currentLoopData = {$iteratee}; \$__env->addLoop(\$__currentLoopData);";

        $iterateLoop = '$__env->incrementLoopIndices(); $loop = $__env->getLastLoop();';

        return "<?php {$empty} = true; {$initLoop} foreach(\$__currentLoopData as {$iteration}): {$iterateLoop} {$empty} = false; ?>";
    }

    /**
     * Compile the while statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileWhile($expression)
    {
        return "<?php while{$expression}: ?>";
    }
}
