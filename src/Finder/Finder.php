<?php
/**
 *
 */

namespace View5\Finder;

trait Finder
{
    /**
     * @var ViewFinder
     */
    protected $finder;

    /**
     * @param $template
     * @return string
     */
    function find($template)
    {
        return $this->finder->find($template);
    }

    /**
     * @param  string  $view
     * @return bool
     */
    function exists($view)
    {
        return (bool) $this->find($view);
    }
}
