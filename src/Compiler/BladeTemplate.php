<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler;

use Mvc5\Config\Overload;

class BladeTemplate
    implements Template
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
        'contentTags'    => ['{{', '}}'],
        'directive'      => [],
        'echoFormat'     => 'htmlspecialchars(%s)',
        'escapedTags'    => ['{{{', '}}}'],
        'extension'      => [],
        'forelseCounter' => 0,
        'footer'         => [],
        'rawTags'        => ['{!!', '!!}'],
        'verbatimBlocks' => [],
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
    function contentTags()
    {
        return $this['contentTags'];
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
     * @return array
     */
    function directives()
    {
        return $this['directive'];
    }

    /**
     * @return array
     */
    function echoFormat()
    {
        return $this['echoFormat'];
    }

    /**
     * @return array
     */
    function escapedTags()
    {
        return $this['escapedTags'];
    }

    /**
     * @return array
     */
    function extensions()
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
        return $this['forelseCounter'];
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
    function rawTags()
    {
        return $this['rawTags'];
    }

    /**
     * @return array
     */
    function verbatimBlocks()
    {
        return $this['verbatimBlocks'];
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this['content'];
    }
}
