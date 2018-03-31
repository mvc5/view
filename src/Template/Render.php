<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template;

use Mvc5\Template\TemplateModel;

trait Render
{
    /**
     *
     */
    use Stack;

    /**
     * @var callable
     */
    protected $engine;

    /**
     * @param TemplateModel $model
     * @return string
     */
    protected function output(TemplateModel $model) : string
    {
        return $this->start() ? $this->finish(($this->engine)($model)) : '';
    }

    /**
     * @param string|TemplateModel $model
     * @param array $vars
     * @return string
     * @throws \Throwable
     */
    function render($model, array $vars = []) : string
    {
        try {

            return $this->output($this->traverse($this->template($model, $vars)));

        } catch (\Throwable $exception) {

            $this->reset();

            throw $exception;
        }
    }

    /**
     * @param string|TemplateModel $model
     * @param array $vars
     * @param string $iterator
     * @param string $empty
     * @param string $result
     * @return string
     * @throws \Throwable
     */
    function renderEach($model, array $vars, string $iterator, string $empty = 'raw|', string $result = '') : string
    {
        foreach($vars as $key => $value) {
            $result .= $this->render($model, ['key' => $key, $iterator => $value]);
        }

        return $vars ? $result : ('raw|' === substr($empty, 0, 4) ? substr($empty, 4) : $this->render($empty));
    }

    /**
     * @param array $names
     * @param array $vars
     * @param array $merge
     * @return string
     * @throws \Throwable
     */
    function renderFirst(array $names, array $vars = [], $merge = []) : string
    {
        foreach($names as $name) {
            if (FilePath::exists($path = $this->find($name))) {
                break;
            }
        }

        return $this->renderInclude((string) $path, $vars, $merge);
    }

    /**
     * @param string|TemplateModel $model
     * @param array $vars
     * @param array $merge
     * @return string
     * @throws \Throwable
     */
    function renderInclude($model, array $vars = [], array $merge = []) : string
    {
        return $this->render($model, $vars + $merge);
    }

    /**
     * @param string $path
     * @param array $vars
     * @param array $merge
     * @return string
     * @throws \Throwable
     */
    function renderIf(string $path, array $vars = [], array $merge = []) : string
    {
        return FilePath::exists($path = $this->find($path)) ? $this->renderInclude((string) $path, $vars, $merge) : '';
    }

    /**
     * @param bool $condition
     * @param string $path
     * @param array $vars
     * @param array $merge
     * @return string
     * @throws \Throwable
     */
    function renderWhen($condition, string $path, array $vars = [], array $merge = []) : string
    {
        return $condition ? $this->render($path, $vars + $merge) : '';
    }

    /**
     * @param array $vars
     * @return array
     */
    function vars(array $vars) : array
    {
        return array_diff_key($vars, ['__env' => 1, '__template' => 1, '__child' => 1, 'this' => 1, '__ob_level__' => 1]);
    }

    /**
     * @param array|string|TemplateModel $model
     * @param array $vars
     * @return mixed
     * @throws \Throwable
     */
    function __invoke($model = null, array $vars = [])
    {
        return !$model instanceof TemplateModel ? $model : $this->render($model, $vars);
    }
}
