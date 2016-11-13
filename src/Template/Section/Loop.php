<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template\Section;

trait Loop
{
    /**
     * @var array
     */
    protected $loopStack = [];

    /**
     * @param array $data
     * @return void
     */
    function addLoop($data)
    {
        $length = count($data);

        $parent = end($this->loopStack);

        $this->loopStack[] = [
            'iteration' => 0,
            'index'     => 0,
            'remaining' => isset($length) ? $length : null,
            'count'     => $length,
            'first'     => true,
            'last'      => isset($length) ? $length == 1 : null,
            'depth'     => count($this->loopStack) + 1,
            'parent'    => $parent ? (object) $parent : null,
        ];
    }

    /**
     * @return null|object
     */
    function getFirstLoop()
    {
        return ($last = end($this->loopStack)) ? (object) $last : null;
    }

    /**
     * @return void
     */
    function incrementLoopIndices()
    {
        $loop = &$this->loopStack[count($this->loopStack) - 1];

        $loop['iteration']++;
        $loop['index'] = $loop['iteration'] - 1;

        $loop['first'] = $loop['iteration'] == 1;

        if (isset($loop['count'])) {
            $loop['remaining']--;

            $loop['last'] = $loop['iteration'] == $loop['count'];
        }
    }

    /**
     * @return void
     */
    function popLoop()
    {
        array_pop($this->loopStack);
    }
}
