<?php
/**
 *
 */

namespace View5;

use function next;
use function reset;

final class Compiler
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Template
     */
    protected $template;

    /**
     * @param Template $template
     * @param array $config
     */
    function __construct(Template $template, array $config = [])
    {
        $this->config = $config;
        $this->template = $template;
    }

    /**
     * @param callable|mixed $compiler
     * @param Template $template
     * @return Template
     */
    protected function call($compiler, Template $template) : Template
    {
        return $compiler ? $compiler($template, $this->delegate()) : $template;
    }

    /**
     * @return \Closure
     */
    protected function delegate() : \Closure
    {
        return function(Template $template) {
            return $this->call(next($this->config), $template);
        };
    }

    /**
     * @param string $content
     * @return string
     */
    function __invoke(string $content) : string
    {
        return (string) $this->call(reset($this->config), $this->template->with('content', $content));
    }
}
