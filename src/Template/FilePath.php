<?php
/**
 *
 */

namespace View5\Template;

use function file_exists;

final class FilePath
{
    /**
     * @var string
     */
    protected string $path;

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
