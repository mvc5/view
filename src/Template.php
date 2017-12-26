<?php
/**
 *
 */

namespace View5;

use Mvc5\Overload;

class Template
    extends Overload
{
    /**
     * @var array
     */
    protected $default = [
        'content' => '',
        'contentTag' => ['{{', '}}'],
        'directive' => [],
        'echoFormat' => 'htmlspecialchars(%s)',
        'escapedTag' => ['{{{', '}}}'],
        'extension' => [],
        'footer' => [],
        'forElseCounter' => 0,
        'json_options' => JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT,
        'import' => [],
        'rawBlocks' => [],
        'rawTag' => ['{!!', '!!}'],
        'token' => []
    ];

    /**
     * @param array $config
     */
    function __construct($config = [])
    {
        parent::__construct($config + $this->default);
    }

    /**
     * @return string
     */
    function content() : string
    {
        return $this['content'];
    }

    /**
     * @return array
     */
    function contentTag() : array
    {
        return $this['contentTag'];
    }

    /**
     * @param $name
     * @return callable|null
     */
    function directive($name)
    {
        return $this['directive'][$name] ?? null;
    }

    /**
     * @return string
     */
    function echoFormat() : string
    {
        return $this['echoFormat'];
    }

    /**
     * @return array
     */
    function escapedTag() : array
    {
        return $this['escapedTag'];
    }

    /**
     * @return array
     */
    function extension() : array
    {
        return $this['extension'];
    }

    /**
     * @return array
     */
    function footer() : array
    {
        return $this['footer'];
    }

    /**
     * @return array
     */
    function forElseCounter() : array
    {
        return $this['forElseCounter'];
    }

    /**
     * @param $value
     * @return string
     */
    function formatEcho(string $value) : string
    {
        return sprintf($this->echoFormat(), $value);
    }

    /**
     * @return array
     */
    function import() : array
    {
        return $this['import'];
    }

    /**
     * @return array
     */
    function rawBlocks() : array
    {
        return $this['rawBlocks'];
    }

    /**
     * @return array
     */
    function rawTag() : array
    {
        return $this['rawTag'];
    }

    /**
     * @return array
     */
    function token() : array
    {
        return $this['token'];
    }

    /**
     * @return string
     */
    function __toString() : string
    {
        return $this->content();
    }
}
