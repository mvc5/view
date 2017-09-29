<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Compiler\Config;

trait Template
{
    /**
     * @var array
     */
    protected $default = [
        'compiler'       => [],
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
    function compiler() : array
    {
        return $this['compiler'] ?? [];
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
        return $this['contentTag'] ?? [];
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
        return $this['escapedTag'] ?? [];
    }

    /**
     * @return array
     */
    function extension() : array
    {
        return $this['extension'] ?? [];
    }

    /**
     * @return array
     */
    function footer() : array
    {
        return $this['footer'] ?? [];
    }

    /**
     * @return array
     */
    function forElseCounter() : array
    {
        return $this['forElseCounter'] ?? [];
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
        return $this['import'] ?? [];
    }

    /**
     * @return array
     */
    function rawTag() : array
    {
        return $this['rawTag'] ?? [];
    }

    /**
     * @return array
     */
    function verbatimBlock() : array
    {
        return $this['verbatimBlock'] ?? [];
    }

    /**
     * @return string
     */
    function __toString() : string
    {
        return $this->content();
    }
}
