<?php
/**
 *
 */

namespace View5\Compiler;

class Engine
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
     * @param callable $compiler
     * @param Template $template
     * @return mixed
     */
    protected function call($compiler, $template)
    {
        return $compiler($template, $this->next());
    }

    /**
     * @return \Closure
     */
    protected function next()
    {
        return function(Template $template) {
            return ($compiler = next($this->stack)) ? $this->call($compiler, $template) : $template;
        };
    }

    /**
     * @param $value
     * @return string
     */
    function compile($value)
    {
        return (string) (!$this->stack ? $value : $this->call(
            reset($this->stack), $this->template->with('content', $value)
        ));
    }
}
