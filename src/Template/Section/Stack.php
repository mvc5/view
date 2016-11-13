<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template\Section;

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
     * @return string
     * @throws \InvalidArgumentException
     */
    function appendSection()
    {
        if (empty($this->sectionStack)) {
            throw new \InvalidArgumentException('Cannot end a section without first starting one.');
        }

        $last = array_pop($this->sectionStack);

        isset($this->section[$last]) ? $this->section[$last] .= ob_get_clean() : $this->section[$last] = ob_get_clean();

        return $last;
    }

    /**
     * @param  string  $section
     * @param  string  $content
     * @return void
     */
    protected function extendSection($section, $content)
    {
        isset($this->section[$section]) &&
            $content = str_replace('@parent', $content, $this->section[$section]);

        $this->section[$section] = $content;
    }

    /**
     * @param $model
     * @param array $vars
     * @return mixed|null|string
     * @throws \Exception
     * @throws \Throwable
     */
    abstract function render($model, array $vars = []);

    /**
     * @param  string  $view
     * @param  array   $data
     * @param  string  $iterator
     * @param  string  $empty
     * @return string
     */
    function renderEach($view, $data, $iterator, $empty = 'raw|')
    {
        $result = '';

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
     * @return void
     */
    function startSection($section, $content = '')
    {
        $content === '' ? ob_start() && $this->sectionStack[] = $section : $this->extendSection($section, $content);
    }

    /**
     * @param  bool $overwrite
     * @return string
     * @throws \InvalidArgumentException
     */
    function stopSection($overwrite = false)
    {
        if (empty($this->sectionStack)) {
            throw new \InvalidArgumentException('Cannot end a section without first starting one.');
        }

        $last = array_pop($this->sectionStack);

        $overwrite ? $this->section[$last] = ob_get_clean() : $this->extendSection($last, ob_get_clean());

        return $last;
    }

    /**
     * @param  string  $section
     * @param  string  $content
     * @return string
     */
    function yieldContent($section, $content = '')
    {
        isset($this->section[$section]) &&
            $content = $this->section[$section];

        $content = str_replace('@@parent', '--parent--holder--', $content);

        return str_replace(
            '--parent--holder--', '@parent', str_replace('@parent', '', $content)
        );
    }

    /**
     * @return string
     */
    function yieldSection()
    {
        return $this->sectionStack ? $this->yieldContent($this->stopSection()) : '';
    }
}
