<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template\Stack;

trait Loop
{
    /**
     * @var array
     */
    protected $loopStack = [];

    /**
     * @param iterable $data
     * @return iterable
     */
    function addLoop(iterable $data) : iterable
    {
        $length = is_array($data) || $data instanceof \Countable ? count($data) : null;

        $parent = end($this->loopStack);

        $this->loopStack[] = [
            'iteration' => 0,
            'index' => 0,
            'remaining' => $length ?? null,
            'count' => $length,
            'first' => true,
            'last' => isset($length) ? $length == 1 : null,
            'depth' => count($this->loopStack) + 1,
            'parent' => $parent ? (object) $parent : null,
        ];

        return $data;
    }

    /**
     * @return object|null
     */
    function getLastLoop()
    {
        return ($last = end($this->loopStack)) ? (object) $last : null;
    }

    /**
     *
     */
    function incrementLoopIndices() : void
    {
        $loop = $this->loopStack[$index = count($this->loopStack) - 1];

        $this->loopStack[$index] = array_merge($this->loopStack[$index], [
            'iteration' => $loop['iteration'] + 1,
            'index' => $loop['iteration'],
            'first' => $loop['iteration'] == 0,
            'remaining' => isset($loop['count']) ? $loop['remaining'] - 1 : null,
            'last' => isset($loop['count']) ? $loop['iteration'] == $loop['count'] - 1 : null,
        ]);
    }

    /**
     *
     */
    function popLoop() : void
    {
        array_pop($this->loopStack);
    }
}
