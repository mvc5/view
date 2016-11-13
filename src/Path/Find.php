<?php
/**
 *
 */

namespace View5\Path;

trait Find
{
    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @param $name
     * @return string
     */
    protected function find($name)
    {
        return $this->finder->find($name);
    }
}
