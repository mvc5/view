<?php
/**
 *
 */

namespace View5;

final class FileContainer
    implements File
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * @var bool
     */
    protected $expired = false;

    /**
     * @param string $directory
     * @param bool $expired
     */
    function __construct(string $directory, bool $expired = false)
    {
        $this->directory = $directory;
        $this->expired = $expired;
    }

    /**
     * @param string $template
     * @param string $path
     * @return bool
     */
    function expired(string $template, string $path) : bool
    {
        return $this->expired || !file_exists($path) ?: filemtime($template) >= filemtime($path);
    }

    /**
     * @param string $path
     * @return string
     */
    function path(string $path) : string
    {
        return $this->directory . DIRECTORY_SEPARATOR . sha1($path) . '.php';
    }

    /**
     * @param string $path
     * @return string
     */
    function read(string $path) : string
    {
        return (string) file_get_contents($path);
    }

    /**
     * @param string $path
     * @param string $content
     * @return bool
     */
    function write(string $path, string $content) : bool
    {
        return (bool) file_put_contents($path, $content);
    }
}
