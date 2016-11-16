<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Config;

use Mvc5\Config\Overload;

trait Template
{
    /**
     *
     */
    use Overload;

    /**
     * @var array
     */
    protected $default = [
        'compiler'       => ['Extensions', 'Statements', 'Comments', 'Echos'],
        'contentTag'     => ['{{', '}}'],
        'directive'      => [],
        'echoFormat'     => 'htmlspecialchars(%s)',
        'escapedTag'     => ['{{{', '}}}'],
        'extension'      => [],
        'forElseCounter' => 0,
        'footer'         => [],
        'rawTag'         => ['{!!', '!!}'],
        'verbatimBlock'  => [],
    ];

    /**
     * @param array $config
     */
    function __construct($config = [])
    {
        $this->config = $config + $this->default;
    }

    /**
     * @return array
     */
    function compiler()
    {
        return $this['compiler'];
    }

    /**
     * @return string
     */
    function content()
    {
        return $this['content'];
    }

    /**
     * @return array
     */
    function contentTag()
    {
        return $this['contentTag'];
    }

    /**
     * @param $name
     * @return array
     */
    function directive($name)
    {
        return isset($this['directive'][$name]) ? $this['directive'][$name] : null;
    }

    /**
     * @return string
     */
    function echoFormat()
    {
        return $this['echoFormat'];
    }

    /**
     * @return array
     */
    function escapedTag()
    {
        return $this['escapedTag'];
    }

    /**
     * @return array
     */
    function extension()
    {
        return $this['extension'];
    }

    /**
     * @return array
     */
    function footer()
    {
        return $this['footer'];
    }

    /**
     * @return array
     */
    function forElseCounter()
    {
        return $this['forElseCounter'];
    }

    /**
     * @param $value
     * @return string
     */
    function formatEcho($value)
    {
        return sprintf($this->echoFormat(), $value);
    }

    /**
     * @return array
     */
    function rawTag()
    {
        return $this['rawTag'];
    }

    /**
     * @return array
     */
    function verbatimBlock()
    {
        return $this['verbatimBlock'];
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this['content'];
    }
}
