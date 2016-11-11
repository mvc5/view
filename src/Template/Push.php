<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template;

trait Push
{
    /**
     * @var array
     */
    protected $pushes = [];

    /**
     * @var array
     */
    protected $pushStack = [];

    /**
     * @var int
     */
    protected $renderCount = 0;

    /**
     * Decrement the rendering counter.
     *
     * @return void
     */
    function decrementRender()
    {
        $this->renderCount--;
    }

    /**
     * Check if there are no active render operations.
     *
     * @return bool
     */
    function doneRendering()
    {
        return $this->renderCount == 0;
    }

    /**
     * Append content to a given push section.
     *
     * @param  string  $section
     * @param  string  $content
     * @return void
     */
    protected function extendPush($section, $content)
    {
        !isset($this->pushes[$section])
            && $this->pushes[$section] = [];

        !isset($this->pushes[$section][$this->renderCount])
            ? $this->pushes[$section][$this->renderCount] = $content
                : $this->pushes[$section][$this->renderCount] .= $content;
    }

    /**
     * Increment the rendering counter.
     *
     * @return void
     */
    function incrementRender()
    {
        $this->renderCount++;
    }

    /**
     * Start injecting content into a push section.
     *
     * @param  string  $section
     * @param  string  $content
     * @return void
     */
    function startPush($section, $content = '')
    {
        $content === '' ? (ob_start() && $this->pushStack[] = $section) : $this->extendPush($section, $content);
    }

    /**
     * Stop injecting content into a push section.
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    function stopPush()
    {
        if (empty($this->pushStack)) {
            throw new \InvalidArgumentException('Cannot end a section without first starting one.');
        }

        $last = array_pop($this->pushStack);

        $this->extendPush($last, ob_get_clean());

        return $last;
    }

    /**
     * Get the string contents of a push section.
     *
     * @param  string  $section
     * @param  string  $default
     * @return string
     */
    function yieldPushContent($section, $default = '')
    {
        return !isset($this->pushes[$section]) ? $default : implode($this->pushes[$section]);
    }

}
