<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler;

use Mvc5\Config\Configuration;

interface Template
    extends Configuration
{
    /**
     * @return array
     */
    function compiler();

    /**
     * @return string
     */
    function content();

    /**
     * Array of opening and closing tags for regular echos.
     *
     * @return array
     */
    function contentTag();

    /**
     * @param $name
     * @return array
     */
    function directive($name);

    /**
     * The "regular" / legacy echo string format.
     *
     * @return array
     */
    function echoFormat();

    /**
     * Array of opening and closing tags for escaped echos.
     *
     * @return array
     */
    function escapedTag();

    /**
     * @return array
     */
    function extension();

    /**
     * @return array
     */
    function footer();

    /**
     * @return array
     */
    function forElseCounter();

    /**
     * @param string $value
     * @return string
     */
    function formatEcho($value);

    /**
     * @return array
     */
    function import();

    /**
     * Array of opening and closing tags for raw echos.
     *
     * @return array
     */
    function rawTag();

    /**
     * @return array
     */
    function verbatimBlock();
}
