<?php
/**
 *
 */

namespace View5\Template;

use function file_exists;

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
     * @param $path
     * @return bool
     */
    static function exists($path) : bool
    {
        return ($path instanceof self) || file_exists($path);
    }

    /**
     * @return string
     */
    function __toString() : string
    {
        return $this->path;
    }
}
