<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template;

trait Stack
{
    /**
     *
     */
    use Stack\Component;
    use Stack\Loop;
    use Stack\Push;
    use Stack\Section;

    /**
     * @var int
     */
    protected $current = 0;

    /**
     * @return int
     */
    protected function current() : int
    {
        return $this->current;
    }

    /**
     * @param string $content
     * @return string
     */
    protected function finish(string $content) : string
    {
        $this->stop();

        $this->valid() && $this->reset();

        return $content;
    }

    /**
     *
     */
    protected function reset()
    {
        $this->current = 0;

        $this->section = [];
        $this->sectionStack = [];

        $this->prepends = [];
        $this->push = [];
        $this->pushStack = [];
    }

    /**
     * @return int
     */
    protected function start() : int
    {
        return ++$this->current;
    }

    /**
     * @return int
     */
    protected function stop() : int
    {
        return --$this->current;
    }

    /**
     * @return bool
     */
    protected function valid() : bool
    {
        return 0 === $this->current;
    }
}
