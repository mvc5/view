<?php
/**
 *
 */

namespace View5\Template;

class FilePath
{
    /**
     * @var null|string
     */
    protected $path;

    /**
     * @param string $path
     */
    function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    function __toString() : string
    {
        return $this->path;
    }
}
