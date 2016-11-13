<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Engine;

trait Cache
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * @var null|string
     */
    protected $extension = 'phtml';

    /**
     * @param string $template
     * @param string $path
     * @return bool
     */
    protected function expired($template, $path)
    {
        return !file_exists($path) ? true : filemtime($template) >= filemtime($path);
    }

    /**
     * @param  string  $path
     * @return string
     */
    protected function path($path)
    {
        return $this->directory . DIRECTORY_SEPARATOR . sha1($path) . '.' . $this->extension;
    }

    /**
     * @param  string  $path
     * @return string
     */
    protected function read($path)
    {
        return file_get_contents($path);
    }

    /**
     * @param string $path
     * @param string $content
     * @return string
     */
    protected function store($path, $content)
    {
        return file_put_contents($path, $content);
    }
}
