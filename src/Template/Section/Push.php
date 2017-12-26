<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template\Section;

use Mvc5\Exception;

trait Push
{
    /**
     * @var array
     */
    protected $push = [];

    /**
     * All of the finished, captured prepend sections.
     *
     * @var array
     */
    protected $prepends = [];

    /**
     * @var array
     */
    protected $pushStack = [];

    /**
     * @param  string  $section
     * @param  string  $content
     */
    protected function extendPush(string $section, string $content)
    {
        !isset($this->push[$section]) && $this->push[$section] = [];

        !isset($this->push[$section][$this->current()])
            ? $this->push[$section][$this->current()] = $content
                : $this->push[$section][$this->current()] .= $content;
    }

    /**
     * @param  string  $section
     * @param  string  $content
     */
    function startPush(string $section, string $content = '')
    {
        $content === '' ? (ob_start() && $this->pushStack[] = $section) : $this->extendPush($section, $content);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    function stopPush() : string
    {
        !$this->pushStack &&
            Exception::invalidArgument('Cannot end a section without first starting one.');

        $last = array_pop($this->pushStack);

        $this->extendPush($last, ob_get_clean());

        return $last;
    }

    /**
     * Start prepending content into a push section.
     *
     * @param  string  $section
     * @param  string  $content
     * @return void
     */
    public function startPrepend(string $section, string $content = '')
    {
        $content === '' ? ob_start() && $this->pushStack[] = $section : $this->extendPrepend($section, $content);
    }

    /**
     * Stop prepending content into a push section.
     *
     * @throws \InvalidArgumentException
     */
    public function stopPrepend()
    {
        !$this->pushStack &&
            Exception::invalidArgument('Cannot end a prepend operation without first starting one.');

        $this->extendPrepend(array_pop($this->pushStack), ob_get_clean());
    }

    /**
     * Prepend content to a given stack.
     *
     * @param string $section
     * @param string $content
     */
    protected function extendPrepend($section, $content)
    {
        !isset($this->prepends[$section]) && $this->prepends[$section] = [];

        $this->prepends[$section][$this->current()] =
            !isset($this->prepends[$section][$this->current()]) ? $content :
                $content . $this->prepends[$section][$this->current()];
    }

    /**
     * @param string $section
     * @param string $default
     * @return string
     */
    function yieldPushContent(string $section, string $default = '') : string
    {
        if (!isset($this->push[$section]) && !isset($this->prepends[$section])) {
            return $default;
        }

        $output = '';

        isset($this->prepends[$section]) &&
            $output .= implode(array_reverse($this->prepends[$section]));

        isset($this->push[$section]) &&
            $output .= implode($this->push[$section]);

        return $output;
    }
}
