<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler;

class Blade
    implements Compiler
{
    /**
     * @var array
     */
    protected $stack;

    /**
     * @var Template
     */
    protected $template;

    /**
     * @param Template $template
     * @param array $stack
     */
    function __construct(Template $template, array $stack = [])
    {
        $this->stack    = $stack;
        $this->template = $template;
    }

    /**
     * @param callable $next
     * @param array $args
     * @return mixed
     */
    protected function call($next, array $args = [])
    {
        return call_user_func_array($next, $args);
    }

    /**
     * @return \Closure
     */
    protected function next()
    {
        return function(Template $template) {
            return ($next = next($this->stack)) ? $this->call($next, [$template, $this->next()]) : $template;
        };
    }

    /**
     * @param $value
     * @return string
     */
    function compile($value)
    {
        return (string) (!$this->stack ? $value : $this->call(
            current($this->stack), [$this->template->with('content', $value), $this->next()]
        ));
    }
}
