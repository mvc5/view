<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template\Stack;

use Mvc5\Exception;

trait Section
{
    /**
     * @var mixed
     */
    protected static $parentPlaceholder = [];

    /**
     * @var array
     */
    protected $section = [];

    /**
     * @var array
     */
    protected $sectionStack = [];

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    function appendSection() : string
    {
        !$this->sectionStack &&
            Exception::invalidArgument('Cannot end a section without first starting one.');

        $last = array_pop($this->sectionStack);

        $this->section[$last] = ($this->section[$last] ?? '') . ob_get_clean();

        return $last;
    }

    /**
     * @param string $section
     * @param string $content
     * @return string
     */
    function content(string $section, string $content = '') : string
    {
        isset($this->section[$section]) && $content = $this->section[$section];

        return str_replace(
            '--parent--holder--', '@parent',
            str_replace($this->parentPlaceholder($section), '', str_replace('@@parent', '--parent--holder--', $content))
        );
    }

    /**
     * @param  string  $section
     * @param  string  $content
     */
    protected function extendSection(string $section, string $content)
    {
        $this->section[$section] = isset($this->section[$section]) ?
            str_replace($this->parentPlaceholder($section), $content, $this->section[$section]) : $content;
    }

    /**
     * Get the parent placeholder for the current request.
     *
     * @param  string  $section
     * @return string
     */
    static function parentPlaceholder($section = '') : string
    {
        return static::$parentPlaceholder[$section] ??
            (static::$parentPlaceholder[$section] = '##parent-placeholder-'.sha1($section).'##');
    }

    /**
     * @return string
     */
    function section() : string
    {
        return $this->sectionStack ? $this->content($this->stopSection()) : '';
    }

    /**
     * @param  string  $section
     * @param  string  $content
     */
    function startSection(string $section, string $content = '')
    {
        $content === '' ? ob_start() && $this->sectionStack[] = $section : $this->extendSection($section, $content);
    }

    /**
     * @param  bool $overwrite
     * @return string
     * @throws \InvalidArgumentException
     */
    function stopSection(bool $overwrite = false) : string
    {
        !$this->sectionStack &&
            Exception::invalidArgument('Cannot end a section without first starting one.');

        $last = array_pop($this->sectionStack);

        $overwrite ? $this->section[$last] = ob_get_clean() : $this->extendSection($last, ob_get_clean());

        return $last;
    }
}
