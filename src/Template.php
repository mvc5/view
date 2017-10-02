<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5;

use Mvc5\Config\Configuration;

interface Template
    extends Configuration
{
    /**
     * @return string
     */
    function content() : string;

    /**
     * Array of opening and closing tags for regular echos.
     *
     * @return array
     */
    function contentTag() : array;

    /**
     * @param $name
     * @return callable|null
     */
    function directive($name);

    /**
     * The "regular" / legacy echo string format.
     *
     * @return string
     */
    function echoFormat() : string;

    /**
     * Array of opening and closing tags for escaped echos.
     *
     * @return array
     */
    function escapedTag() : array;

    /**
     * @return array
     */
    function extension() : array;

    /**
     * @return array
     */
    function footer() : array;

    /**
     * @return array
     */
    function forElseCounter() : array;

    /**
     * @param string $value
     * @return string
     */
    function formatEcho(string $value) : string;

    /**
     * @return array
     */
    function import() : array;

    /**
     * Array of opening and closing tags for raw echos.
     *
     * @return array
     */
    function rawTag() : array;

    /**
     * @return array
     */
    function token() : array;

    /**
     * @return array
     */
    function verbatimBlock() : array;

    /**
     * @return string
     */
    function __toString() : string;
}
