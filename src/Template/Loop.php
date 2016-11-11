<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template;

trait Loop
{
    /**
     * @var array
     */
    protected $loopsStack = [];

    /**
     * Add new loop to the stack.
     *
     * @param  array  $data
     * @return void
     */
    function addLoop($data)
    {
        $length = count($data);

        $parent = end($this->loopsStack);

        $this->loopsStack[] = [
            'iteration' => 0,
            'index'     => 0,
            'remaining' => isset($length) ? $length : null,
            'count'     => $length,
            'first'     => true,
            'last'      => isset($length) ? $length == 1 : null,
            'depth'     => count($this->loopsStack) + 1,
            'parent'    => $parent ? (object) $parent : null,
        ];
    }

    /**
     * Increment the top loop's indices.
     *
     * @return void
     */
    function incrementLoopIndices()
    {
        $loop = &$this->loopsStack[count($this->loopsStack) - 1];

        $loop['iteration']++;
        $loop['index'] = $loop['iteration'] - 1;

        $loop['first'] = $loop['iteration'] == 1;

        if (isset($loop['count'])) {
            $loop['remaining']--;

            $loop['last'] = $loop['iteration'] == $loop['count'];
        }
    }

    /**
     * Pop a loop from the top of the loop stack.
     *
     * @return void
     */
    function popLoop()
    {
        array_pop($this->loopsStack);
    }

    /**
     * Get an instance of the first loop in the stack.
     *
     * @return null|object
     */
    function getFirstLoop()
    {
        return ($last = end($this->loopsStack)) ? (object) $last : null;
    }

    /**
     * Get the entire loop stack.
     *
     * @return array
     */
    function getLoopStack()
    {
        return $this->loopsStack;
    }
}
