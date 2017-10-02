<?php
/**
 *
 */

namespace View5;

interface File
{
    /**
     * @param string $template
     * @param string $path
     * @return bool
     */
    function expired(string $template, string $path) : bool;

    /**
     * @param string $path
     * @return string
     */
    function path(string $path) : string;

    /**
     * @param string $path
     * @return string
     */
    function read(string $path) : string;

    /**
     * @param string $path
     * @param string $content
     * @return bool
     */
    function write(string $path, string $content) : bool;
}
