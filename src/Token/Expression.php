<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token;

use View5\Template;

class Expression
{
    /**
     * @var array
     */
    static $import = [];

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
     * Compile the break statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileBreak(string $expression) : string
    {
        return $expression ? "<?php if{$expression} break; ?>" : '<?php break; ?>';
    }

    /**
     * Compile the continue statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileContinue(string $expression) : string
    {
        return $expression ? "<?php if{$expression} continue; ?>" : '<?php continue; ?>';
    }

    /**
     * Compile the each statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileEach(string $expression) : string
    {
        return "<?php echo \$__env->renderEach{$expression}; ?>";
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
        return "<?php elseif{$expression}: ?>";
    }

    /**
     * Compile the for-else-empty statements into valid PHP.
     *
     * @param string $expression
     * @param Template $template
     * @return string
     */
    protected function compileEmpty(string $expression, Template $template) : string
    {
        $empty = '$__empty_'.$template['forElseCounter']--;

        return "<?php endforeach; \$__env->popLoop(); \$loop = \$__env->getFirstLoop(); if ({$empty}): ?>";
    }

    /**
     * Compile the end-for statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndFor() : string
    {
        return '<?php endfor; ?>';
    }

    /**
     * Compile the end-for-each statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndForEach() : string
    {
        return '<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>';
    }

    /**
     * Compile the end-for-else statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndForElse() : string
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
     * Compile end-php statement into valid PHP.
     *
     * @return string
     */
    protected function compileEndPhp() : string
    {
        return ' ?>';
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
     * Compile the end-section statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndSection() : string
    {
        return '<?php $__env->stopSection(); ?>';
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
     * Compile the end-while statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndWhile() : string
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
    protected function compileExtends(string $expression, Template $template) : string
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
    protected function compileFor(string $expression) : string
    {
        return "<?php for{$expression}: ?>";
    }

    /**
     * Compile the foreach statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileForEach(string $expression) : string
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
    protected function compileForElse(string $expression, Template $template) : string
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
    protected function compileHasSection(string $expression) : string
    {
        return "<?php if (! empty(trim(\$__env->yieldContent{$expression}))): ?>";
    }

    /**
     * Compile the if statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileIf(string $expression) : string
    {
        return "<?php if{$expression}: ?>";
    }

    /**
     * @param $expression
     * @return string
     */
    protected function compileImport(string $expression) : string
    {
        return $this->import($expression);
    }

    /**
     * Compile the include statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileInclude(string $expression) : string
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
    protected function compileIncludeIf(string $expression) : string
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
    protected function compileInject(string $expression) : string
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
    protected function compileOverwrite() : string
    {
        return '<?php $__env->stopSection(true); ?>';
    }

    /**
     * Compile the raw PHP statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compilePhp(string $expression) : string
    {
        return $expression ? "<?php {$expression}; ?>" : '<?php ';
    }

    /**
     * Compile the push statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compilePush(string $expression) : string
    {
        return "<?php \$__env->startPush{$expression}; ?>";
    }

    /**
     * Compile the section statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileSection(string $expression) : string
    {
        return "<?php \$__env->startSection{$expression}; ?>";
    }

    /**
     * Compile the show statements into valid PHP.
     *
     * @return string
     */
    protected function compileShow() : string
    {
        return '<?php echo $__env->yieldSection(); ?>';
    }

    /**
     * Compile the stack statements into the content.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileStack(string $expression) : string
    {
        return "<?php echo \$__env->yieldPushContent{$expression}; ?>";
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
     * Compile the unless statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileUnless(string $expression) : string
    {
        return "<?php if (! $expression): ?>";
    }

    /**
     * Compile the unset statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileUnset(string $expression) : string
    {
        return "<?php unset{$expression}; ?>";
    }

    /**
     * @param $expression
     * @return string
     */
    protected function compileUse(string $expression) : string
    {
        return "<?php use " . static::stripParentheses($expression) . "; ?>";
    }

    /**
     * Compile the yield statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileYield(string $expression) : string
    {
        return "<?php echo \$__env->yieldContent{$expression}; ?>";
    }

    /**
     * Compile the while statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileWhile(string $expression) : string
    {
        return "<?php while{$expression}: ?>";
    }

    /**
     * @param string $namespace
     * @return string
     */
    static function import(string $namespace) : string
    {
        $namespace = strtolower(static::stripParentheses($namespace));

        if (isset(static::$import[$namespace])) {
            return static::$import[$namespace];
        }

        $length = strlen($namespace);

        $use = [];

        foreach(get_defined_functions()['user'] as $f) {
            $namespace === substr($f, 0, $length) && $use[] = $f;
        }

        return !$use ? '' : static::$import[$namespace] =
            '<?php use function ' . implode('; use function ', $use) . '; ?>' . "\n";
    }

    /**
     * Strip the parentheses from the given expression.
     *
     * @param  string  $expression
     * @return string
     */
    static function stripParentheses(string $expression) : string
    {
        '' !== $expression && '(' === $expression[0]
            && $expression = substr($expression, 1, -1);

        return trim($expression);
    }

    /**
     * Compile Blade statements that start with "@".
     *
     * @param Template $template
     * @param string $value
     * @return string
     */
    function __invoke(Template $template, string $value) : string
    {
        $match = function ($match) use($template) {
            if (false !== strpos($match[1], '@')) {
                $match[0] = isset($match[3]) ? $match[1] . $match[3] : $match[1];
            } elseif ($directive = $template->directive($match[1])) {
                $match[0] = $directive($match[3] ?? '', $template, $template);
            } elseif (method_exists($this, $method = 'compile' . $match[1])) {
                $match[0] = $this->$method($match[3] ?? '', $template);
            }

            return isset($match[3]) ? $match[0] : $match[0].$match[2];
        };

        return preg_replace_callback('/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', $match, $value);
    }
}
