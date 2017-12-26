<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

trait Includes
{
    /**
     * @var array
     */
    static $import = [];

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

        return "<?php echo \$__env->render($expression, \$__env->vars(get_defined_vars())); ?>";
    }

    /**
     * Compile the include-first statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileIncludeFirst($expression)
    {
        $expression = $this->stripParentheses($expression);

        return "<?php echo \$__env->renderFirst($expression, \$__env->vars(get_defined_vars())); ?>";
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

        return "<?php echo \$__env->renderIf($expression, \$__env->vars(get_defined_vars())); ?>";
    }

    /**
     * Compile the include-when statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileIncludeWhen($expression)
    {
        $expression = $this->stripParentheses($expression);

        return "<?php echo \$__env->renderWhen($expression, \$__env->vars(get_defined_vars())); ?>";
    }

    /**
     * @param $expression
     * @return string
     */
    protected function compileUse(string $expression) : string
    {
        return "<?php use " . $this->stripParentheses($expression) . "; ?>";
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
}
