<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler;

trait Cache
{
    /**
     * @var string
     */
    protected $cache_dir;

    /**
     * @var null|string
     */
    protected $cache_extension = 'phtml';

    /**
     * @param  string  $path
     * @return string
     */
    function path($path)
    {
        return $this->cache_dir . DIRECTORY_SEPARATOR . sha1($path) . '.' . $this->cache_extension;
    }

    /**
     * @param  string  $path
     * @return bool
     */
    function expired($path)
    {
        return !file_exists($compiled = $this->path($path)) ? true : filemtime($path) >= filemtime($compiled);
    }
}
