<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template;

trait Section
{
    /**
     *
     */
    use Section\Loop;
    use Section\Push;
    use Section\Stack;

    /**
     * @var int
     */
    protected $current = 0;

    /**
     * @return int
     */
    protected function current()
    {
        return $this->current;
    }

    /**
     * @param null|string $content
     * @return null|string
     */
    protected function finish($content = null)
    {
        $this->stop();

        $this->valid() && $this->reset();

        return $content;
    }

    /**
     * @return void
     */
    protected function reset()
    {
        $this->current = 0;

        $this->section = [];
        $this->sectionStack = [];

        $this->push = [];
        $this->pushStack = [];
    }

    /**
     * @return int
     */
    protected function start()
    {
        return ++$this->current;
    }

    /**
     * @return int
     */
    protected function stop()
    {
        return --$this->current;
    }

    /**
     * @return bool
     */
    protected function valid()
    {
        return 0 === $this->current;
    }
}
