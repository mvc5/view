<?php
/**
 *
 */

namespace View5;

interface View
    extends \Mvc5\View\View
{
    /**
     * @param  array  $data
     */
    function addLoop(array $data);

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    function appendSection() : string;

    /**
     * @return object|null
     */
    function getFirstLoop();

    /**
     *
     */
    function incrementLoopIndices();

    /**
     *
     */
    function popLoop();

    /**
     * @param  string  $view
     * @param  array   $data
     * @param  string  $iterator
     * @param  string  $empty
     * @return string
     */
    function renderEach(string $view, array $data, string $iterator, string $empty = 'raw|') : string;

    /**
     * @param  string  $section
     * @param  string  $content
     */
    function startPush(string $section, string $content = '');

    /**
     * @param  string  $section
     * @param  string  $content
     */
    function startSection(string $section, string $content = '');

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    function stopPush() : string;

    /**
     * @param bool $overwrite
     * @return string
     * @throws \InvalidArgumentException
     */
    function stopSection(bool $overwrite = false) : string;

    /**
     * @param  string  $section
     * @param  string  $content
     * @return string
     */
    function yieldContent(string $section, string $content = '') : string;

    /**
     * @param  string  $section
     * @param  string  $default
     * @return string
     */
    function yieldPushContent(string $section, string $default = '') : string;

    /**
     * @return string
     */
    function yieldSection() : string;
}
