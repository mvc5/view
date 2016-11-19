<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Compile;

use View5\Compiler\Template;

trait Expression
{
    /**
     * Compile the append statements into valid PHP.
     *
     * @return string
     */
    protected function compileAppend()
    {
        return '<?php $__env->appendSection(); ?>';
    }

    /**
     * Compile the break statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileBreak($expression)
    {
        return $expression ? "<?php if{$expression} break; ?>" : '<?php break; ?>';
    }

    /**
     * Compile the continue statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileContinue($expression)
    {
        return $expression ? "<?php if{$expression} continue; ?>" : '<?php continue; ?>';
    }

    /**
     * Compile the each statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileEach($expression)
    {
        return "<?php echo \$__env->renderEach{$expression}; ?>";
    }

    /**
     * Compile the else statements into valid PHP.
     *
     * @return string
     */
    protected function compileElse()
    {
        return '<?php else: ?>';
    }

    /**
     * Compile the else-if statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileElseIf($expression)
    {
        return "<?php elseif{$expression}: ?>";
    }

    /**
     * Compile the for-else-empty statements into valid PHP.
     *
     * @param  string  $expression
     * @param Template $template
     * @return string
     */
    protected function compileEmpty($expression, $template)
    {
        $empty = '$__empty_'.$template['forElseCounter']--;

        return "<?php endforeach; \$__env->popLoop(); \$loop = \$__env->getFirstLoop(); if ({$empty}): ?>";
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
        return '<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>';
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
     * Compile the end-if statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndIf()
    {
        return '<?php endif; ?>';
    }

    /**
     * Compile end-php statement into valid PHP.
     *
     * @return string
     */
    protected function compileEndPhp()
    {
        return ' ?>';
    }

    /**
     * Compile the end-push statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndPush()
    {
        return '<?php $__env->stopPush(); ?>';
    }

    /**
     * Compile the end-section statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndSection()
    {
        return '<?php $__env->stopSection(); ?>';
    }

    /**
     * Compile the end-unless statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndUnless()
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
     * Compile the extends statements into valid PHP.
     *
     * @param  string  $expression
     * @param Template $template
     * @return string
     */
    protected function compileExtends($expression, $template)
    {
        $expression = $this->stripParentheses($expression);

        $data = "<?php echo \$__env->render($expression, array_diff_key(get_defined_vars(), ['__template' => 1, '__child' => 1, 'this' => 1, '__ob_level__' => 1])); ?>";

        $template['footer'][] = $data;

        return '';
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
     * Compile the foreach statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileForEach($expression)
    {
        preg_match('/\( *(.*) +as *(.*)\)$/is', $expression, $matches);

        $loopData = trim($matches[1]);

        $iteration = trim($matches[2]);

        $initLoop = "\$__currentLoopData = {$loopData}; \$__env->addLoop(\$__currentLoopData);";

        $iterateLoop = '$__env->incrementLoopIndices(); $loop = $__env->getFirstLoop();';

        return "<?php {$initLoop} foreach(\$__currentLoopData as {$iteration}): {$iterateLoop} ?>";
    }

    /**
     * Compile the for-else statements into valid PHP.
     *
     * @param  string  $expression
     * @param Template $template
     * @return string
     */
    protected function compileForElse($expression, $template)
    {
        $empty = '$__empty_'.++$template['forElseCounter'];

        preg_match('/\( *(.*) +as *(.*)\)$/is', $expression, $matches);

        $loopData = trim($matches[1]);

        $iteration = trim($matches[2]);

        $initLoop = "\$__currentLoopData = {$loopData}; \$__env->addLoop(\$__currentLoopData);";

        $iterateLoop = '$__env->incrementLoopIndices(); $loop = $__env->getFirstLoop();';

        return "<?php {$empty} = true; {$initLoop} foreach(\$__currentLoopData as {$iteration}): {$iterateLoop} {$empty} = false; ?>";
    }

    /**
     * Compile the has section statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileHasSection($expression)
    {
        return "<?php if (! empty(trim(\$__env->yieldContent{$expression}))): ?>";
    }

    /**
     * Compile the if statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileIf($expression)
    {
        return "<?php if{$expression}: ?>";
    }

    /**
     * Compile the include statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileInclude($expression)
    {
        $expression = $this->stripParentheses($expression);

        return "<?php echo \$__env->render($expression, array_diff_key(get_defined_vars(), ['__template' => 1, '__child' => 1, 'this' => 1, '__ob_level__' => 1])); ?>";
    }

    /**
     * Compile the include statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileIncludeIf($expression)
    {
        $expression = $this->stripParentheses($expression);

        return "<?php if (\$__env->exists($expression)) echo \$__env->render($expression, array_diff_key(get_defined_vars(), ['__template' => 1, '__child' => 1, 'this' => 1, '__ob_level__' => 1])); ?>";
    }

    /**
     * Compile the inject statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileInject($expression)
    {
        $segments = array_map('trim', explode(',', $this->stripParentheses($expression)));

        $plugin = str_replace(['\'', '"'], '', [array_shift($segments), array_shift($segments)]);

        return '<?php $'.$plugin[0]." = \$this->plugin('".$plugin[1]."', [" . implode(',', $segments) . "]); ?>";
    }

    /**
     * Compile the overwrite statements into valid PHP.
     *
     * @return string
     */
    protected function compileOverwrite()
    {
        return '<?php $__env->stopSection(true); ?>';
    }

    /**
     * Compile the raw PHP statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compilePhp($expression)
    {
        return $expression ? "<?php {$expression}; ?>" : '<?php ';
    }

    /**
     * Compile the push statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compilePush($expression)
    {
        return "<?php \$__env->startPush{$expression}; ?>";
    }

    /**
     * Compile the section statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileSection($expression)
    {
        return "<?php \$__env->startSection{$expression}; ?>";
    }

    /**
     * Compile the show statements into valid PHP.
     *
     * @return string
     */
    protected function compileShow()
    {
        return '<?php echo $__env->yieldSection(); ?>';
    }

    /**
     * Compile the stack statements into the content.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileStack($expression)
    {
        return "<?php echo \$__env->yieldPushContent{$expression}; ?>";
    }

    /**
     * Compile the stop statements into valid PHP.
     *
     * @return string
     */
    protected function compileStop()
    {
        return '<?php $__env->stopSection(); ?>';
    }

    /**
     * Compile the unless statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileUnless($expression)
    {
        return "<?php if (! $expression): ?>";
    }

    /**
     * Compile the unset statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileUnset($expression)
    {
        return "<?php unset{$expression}; ?>";
    }

    /**
     * Compile the yield statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileYield($expression)
    {
        return "<?php echo \$__env->yieldContent{$expression}; ?>";
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

    /**
     * Strip the parentheses from the given expression.
     *
     * @param  string  $expression
     * @return string
     */
    static function stripParentheses($expression)
    {
        '' !== $expression && '(' === $expression[0]
            && $expression = substr($expression, 1, -1);

        return $expression;
    }
}
