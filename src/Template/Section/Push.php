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
     * @var array
     */
    protected $pushStack = [];

    /**
     * @param  string  $section
     * @param  string  $content
     */
    protected function extendPush(string $section, string $content)
    {
        !isset($this->push[$section])
            && $this->push[$section] = [];

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
     * @param string $section
     * @param string $default
     * @return string
     */
    function yieldPushContent(string $section, string $default = '') : string
    {
        return !isset($this->push[$section]) ? $default : implode($this->push[$section]);
    }
}
