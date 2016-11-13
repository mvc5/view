<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template\Section;

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
     * @return int
     */
    protected abstract function current();

    /**
     * @param  string  $section
     * @param  string  $content
     * @return void
     */
    protected function extendPush($section, $content)
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
     * @return void
     */
    function startPush($section, $content = '')
    {
        $content === '' ? (ob_start() && $this->pushStack[] = $section) : $this->extendPush($section, $content);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    function stopPush()
    {
        if (!$this->pushStack) {
            throw new \InvalidArgumentException('Cannot end a section without first starting one.');
        }

        $last = array_pop($this->pushStack);

        $this->extendPush($last, ob_get_clean());

        return $last;
    }

    /**
     * @param  string  $section
     * @param  string  $default
     * @return string
     */
    function yieldPushContent($section, $default = '')
    {
        return !isset($this->push[$section]) ? $default : implode($this->push[$section]);
    }
}
