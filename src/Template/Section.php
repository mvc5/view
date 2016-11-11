<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template;

trait Section
{
    /**
     *
     */
    use Loop;
    use Push;

    /**
     * @var array
     */
    protected $sections = [];

    /**
     * @var array
     */
    protected $sectionStack = [];

    /**
     * Stop injecting content into a section and append it.
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    function appendSection()
    {
        if (empty($this->sectionStack)) {
            throw new \InvalidArgumentException('Cannot end a section without first starting one.');
        }

        $last = array_pop($this->sectionStack);

        isset($this->sections[$last]) ? $this->sections[$last] .= ob_get_clean() : $this->sections[$last] = ob_get_clean();

        return $last;
    }

    /**
     * Append content to a given section.
     *
     * @param  string  $section
     * @param  string  $content
     * @return void
     */
    protected function extendSection($section, $content)
    {
        isset($this->sections[$section]) &&
            $content = str_replace('@parent', $content, $this->sections[$section]);

        $this->sections[$section] = $content;
    }

    /**
     * Flush all of the section contents.
     *
     * @return void
     */
    function flushSections()
    {
        $this->renderCount = 0;

        $this->sections = [];
        $this->sectionStack = [];

        $this->pushes = [];
        $this->pushStack = [];
    }

    /**
     * Flush all of the section contents if done rendering.
     *
     * @return void
     */
    function flushSectionsIfDoneRendering()
    {
        $this->doneRendering() && $this->flushSections();
    }

    /**
     * Check if section exists.
     *
     * @param  string  $name
     * @return bool
     */
    function hasSection($name)
    {
        return array_key_exists($name, $this->sections);
    }

    /**
     * Get the rendered contents of a partial from a loop.
     *
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
                $result .= $this->render(['__template' => $view] + ['key' => $key, $iterator => $value]);
            }
        } else {
            $result = 'raw|' === substr($empty, 4) ? substr($empty, 4) : $this->render($empty);
        }

        return $result;
    }

    /**
     * Start injecting content into a section.
     *
     * @param  string  $section
     * @param  string  $content
     * @return void
     */
    function startSection($section, $content = '')
    {
        $content === '' ? ob_start() && $this->sectionStack[] = $section : $this->extendSection($section, $content);
    }

    /**
     * Stop injecting content into a section.
     *
     * @param  bool  $overwrite
     * @return string
     * @throws \InvalidArgumentException
     */
    function stopSection($overwrite = false)
    {
        if (empty($this->sectionStack)) {
            throw new \InvalidArgumentException('Cannot end a section without first starting one.');
        }

        $last = array_pop($this->sectionStack);

        $overwrite ? ($this->sections[$last] = ob_get_clean()) : $this->extendSection($last, ob_get_clean());

        return $last;
    }

    /**
     * Get the string contents of a section.
     *
     * @param  string  $section
     * @param  string  $default
     * @return string
     */
    function yieldContent($section, $default = '')
    {
        $sectionContent = $default;

        isset($this->sections[$section]) &&
            $sectionContent = $this->sections[$section];

        $sectionContent = str_replace('@@parent', '--parent--holder--', $sectionContent);

        return str_replace(
            '--parent--holder--', '@parent', str_replace('@parent', '', $sectionContent)
        );
    }

    /**
     * Stop injecting content into a section and return its contents.
     *
     * @return string
     */
    function yieldSection()
    {
        return empty($this->sectionStack) ? '' : $this->yieldContent($this->stopSection());
    }
}
