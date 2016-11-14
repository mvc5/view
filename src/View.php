<?php
/**
 *
 */

namespace View5;

use Mvc5\View\View as _View;

interface View
    extends _View
{
    /**
     * @param  array  $data
     * @return void
     */
    function addLoop($data);

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    function appendSection();

    /**
     * @return null|object
     */
    function getFirstLoop();

    /**
     * @return void
     */
    function incrementLoopIndices();

    /**
     * @return void
     */
    function popLoop();

    /**
     * @param  string  $view
     * @param  array   $data
     * @param  string  $iterator
     * @param  string  $empty
     * @return string
     */
    function renderEach($view, $data, $iterator, $empty = 'raw|');

    /**
     * @param  string  $section
     * @param  string  $content
     * @return void
     */
    function startPush($section, $content = '');

    /**
     * @param  string  $section
     * @param  string  $content
     * @return void
     */
    function startSection($section, $content = '');

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    function stopPush();

    /**
     * @param bool $overwrite
     * @return string
     * @throws \InvalidArgumentException
     */
    function stopSection($overwrite = false);

    /**
     * @param  string  $section
     * @param  string  $content
     * @return string
     */
    function yieldContent($section, $content = '');

    /**
     * @param  string  $section
     * @param  string  $default
     * @return string
     */
    function yieldPushContent($section, $default = '');

    /**
     * @return string
     */
    function yieldSection();
}
