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
     * @param string  $view
     * @param array   $data
     * @param string  $iterator
     * @param string  $empty
     * @param string $result
     * @return string
     */
    function renderEach(string $view, array $data, string $iterator, string $empty = 'raw|', string $result = '') : string
    {
        foreach($data as $key => $value) {
            $result .= $this->render($view, ['key' => $key, $iterator => $value]);
        }

        return $data ? $result : ('raw|' === substr($empty, 0, 4) ? substr($empty, 4) : $this->render($empty));
    }

    /**
     * @param array $names
     * @param array $vars
     * @return string
     * @throws \Throwable
     */
    function renderFirst(array $names, array $vars = []) : string
    {
        foreach($names as $name) {
            $path = $this->find($name);
            if ($path instanceof FilePath || file_exists($path)) {
                break;
            }
        }

        return $this->render($this->create($name, ['__template' => (string) $path]), $vars);
    }

    /**
     * @param string|TemplateModel $model
     * @param array $vars
     * @return string
     * @throws \Throwable
     */
    function renderIf($model, array $vars = []) : string
    {
        return (($path = $this->find($model)) instanceof FilePath || file_exists($path)) ?
            $this->render($this->create($model, ['__template' => (string) $path]), $vars) : '';
    }

    /**
     * @param bool $condition
     * @param string|TemplateModel $model
     * @param array $vars
     * @return string
     * @throws \Throwable
     */
    function renderWhen($condition, $model, array $vars = []) : string
    {
        return $condition ? $this->render($model, $vars) : '';
    }

    /**
     * @param array $vars
     * @return array
     */
    function vars(array $vars) : array
    {
        return array_diff_key($vars, ['__template' => 1, '__child' => 1, 'this' => 1, '__ob_level__' => 1]);
    }

    /**
     * @param array|string|TemplateModel $model
     * @param array $vars
     * @return mixed
     */
    function __invoke($model = null, array $vars = [])
    {
        return !$model instanceof TemplateModel ? $model : $this->render($model, $vars);
    }
}
