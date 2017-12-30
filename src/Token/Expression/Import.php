<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Token\Expression;

trait Import
{
    /**
     * @var array
     */
    static $import = [];

    /**
     * @param $expression
     * @return string
     */
    protected function compileImport(string $expression) : string
    {
        return $this->import($expression);
    }

    /**
     * @param $expression
     * @return string
     */
    protected function compileUse(string $expression) : string
    {
        return '<?php use ' . $this->expr($expression) . '; ?>';
    }

    /**
     * @param string $namespace
     * @return string
     */
    static function import(string $namespace) : string
    {
        $namespace = strtolower(static::expr($namespace));

        if (isset(static::$import[$namespace])) {
            return static::$import[$namespace];
        }

        $length = strlen($namespace);

        $use = [];

        foreach(get_defined_functions()['user'] as $f) {
            $namespace === substr($f, 0, $length) && $use[] = $f;
        }

        return !$use ? '' : static::$import[$namespace] = '<?php use function ' . implode('; use function ', $use) . '; ?>';
    }
}
