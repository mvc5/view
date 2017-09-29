<?php
/**
 *
 */

namespace View5\Engine;

trait Storage
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
     * @var null|string
     */
    protected $extension = 'phtml';

    /**
     * @param string $template
     * @param string $path
     * @return bool
     */
    protected function expired(string $template, string $path) : bool
    {
        return $this->expired || !file_exists($path) ?: filemtime($template) >= filemtime($path);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function path(string $path) : string
    {
        return $this->directory . DIRECTORY_SEPARATOR . sha1($path) . '.' . $this->extension;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function read(string $path) : string
    {
        return (string) file_get_contents($path);
    }

    /**
     * @param string $path
     * @param string $content
     * @return bool
     */
    protected function store(string $path, string $content) : bool
    {
        return (bool) file_put_contents($path, $content);
    }
}
