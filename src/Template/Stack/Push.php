<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template\Stack;

use Mvc5\Exception;

use function array_pop;
use function array_reverse;
use function implode;
use function ob_get_clean;
use function ob_start;

trait Push
{
    /**
     * All of the finished, captured prepend sections.
     *
     * @var array
     */
    protected array $prepends = [];

    /**
     * @var array
     */
    protected array $push = [];

    /**
     * @var array
     */
    protected array $pushStack = [];

    /**
     * Prepend content to a given stack.
     *
     * @param string $section
     * @param string $content
     */
    protected function extendPrepend($section, $content) : void
    {
        $this->prepends[$section][$this->current()] = $content . ($this->prepends[$section][$this->current()] ?? '');
    }

    /**
     * @param  string  $section
     * @param  string  $content
     */
    protected function extendPush(string $section, string $content) : void
    {
        $this->push[$section][$this->current()] = ($this->push[$section][$this->current()] ?? '') . $content;
    }

    /**
     * @param string $section
     * @param string $default
     * @return string
     */
    function stack(string $section, string $default = '') : string
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

    /**
     * Start prepending content into a push section.
     *
     * @param  string  $section
     * @param  string  $content
     */
    public function startPrepend(string $section, string $content = '') : void
    {
        $content === '' ? ob_start() && $this->pushStack[] = $section : $this->extendPrepend($section, $content);
    }

    /**
     * @param  string  $section
     * @param  string  $content
     */
    function startPush(string $section, string $content = '') : void
    {
        $content === '' ? (ob_start() && $this->pushStack[] = $section) : $this->extendPush($section, $content);
    }

    /**
     * Stop prepending content into a push section.
     *
     * @throws \InvalidArgumentException
     */
    function stopPrepend() : void
    {
        !$this->pushStack &&
            Exception::invalidArgument('Cannot end a prepend operation without first starting one.');

        $this->extendPrepend(array_pop($this->pushStack), ob_get_clean());
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
}
