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

        isset($this->section[$last]) ? $this->section[$last] .= ob_get_clean() : $this->section[$last] = ob_get_clean();

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
    protected static function parentPlaceholder($section = '')
    {
        if (! isset(static::$parentPlaceholder[$section])) {
            static::$parentPlaceholder[$section] = '##parent-placeholder-'.sha1($section).'##';
        }

        return static::$parentPlaceholder[$section];
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
     * @param string  $view
     * @param array   $data
     * @param string  $iterator
     * @param string  $empty
     * @param string $result
     * @return string
     */
    function renderEach(string $view, array $data, string $iterator, string $empty = 'raw|', string $result = '') : string
    {
        // If data in the array, we will loop through the data and append
        // an instance of the partial view to the final result HTML passing in the
        // iterated value of this data array, allowing the views to access them.
        // If there is no data in the array, we will render the contents of the empty
        // view. Alternatively, the "empty view" could be a raw string that begins
        // with "raw|" for convenience and to let this know that it is a string.

        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $result .= $this->render($view, ['key' => $key, $iterator => $value]);
            }
        } else {
            $result = 'raw|' === substr($empty, 4) ? substr($empty, 4) : $this->render($empty);
        }

        return $result;
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

    /**
     * @param string $section
     * @param string $content
     * @return string
     */
    function yieldContent(string $section, string $content = '') : string
    {
        isset($this->section[$section]) &&
            $content = $this->section[$section];

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
