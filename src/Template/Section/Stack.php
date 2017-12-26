<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template\Section;

use Mvc5\Exception;

trait Stack
{
    /**
     * @var array
     */
    protected $section = [];

    /**
     * @var array
     */
    protected $sectionStack = [];

    /**
     * @var mixed
     */
    protected static $parentPlaceholder = [];

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
     * @param  string  $section
     * @param  string  $content
     */
    protected function extendSection(string $section, string $content)
    {
        isset($this->section[$section]) &&
            $content = str_replace($this->parentPlaceholder($section), $content, $this->section[$section]);

        $this->section[$section] = $content;
    }

    /**
     * Get the parent placeholder for the current request.
     *
     * @param  string  $section
     * @return string
     */
    static function parentPlaceholder($section = '')
    {
        return static::$parentPlaceholder[$section] ??
            (static::$parentPlaceholder[$section] = '##parent-placeholder-'.sha1($section).'##');
    }

    /**
     * @param $model
     * @param array $vars
     * @return string
     * @throws \Exception
     * @throws \Throwable
     */
    abstract function render($model, array $vars = []) : string;

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

    /**
     * @param string $section
     * @param string $content
     * @return string
     */
    function yieldContent(string $section, string $content = '') : string
    {
        isset($this->section[$section]) && $content = $this->section[$section];

        return str_replace(
            '--parent--holder--', '@parent',
            str_replace($this->parentPlaceholder($section), '', str_replace('@@parent', '--parent--holder--', $content))
        );
    }

    /**
     * @return string
     */
    function yieldSection() : string
    {
        return $this->sectionStack ? $this->yieldContent($this->stopSection()) : '';
    }
}
