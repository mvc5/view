<?php
/**
 *
 */

namespace View5\Template;

use Mvc5\Template\TemplateModel;
use Mvc5\View\Template\Traverse;

trait Render
{
    /**
     *
     */
    use Section;
    use Traverse;

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
        // If data in the array, we will loop through the data and append
        // an instance of the partial view to the final result HTML passing in the
        // iterated value of this data array, allowing the views to access them.
        // If there is no data in the array, we will render the contents of the empty
        // view. Alternatively, the "empty view" could be a raw string that begins
        // with "raw|" for convenience and to let this know that it is a string.

        if (count($data) > 0) {
            foreach($data as $key => $value) {
                $result .= $this->render($view, ['key' => $key, $iterator => $value]);
            }

            return $result;
        }

        return 'raw|' === substr($empty, 0, 4) ? substr($empty, 4) : $this->render($empty);
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
            if (file_exists($template = $this->find($name))) {
                break;
            }
        }

        return $this->render($this->create($name, ['__template' => $template]), $vars);
    }

    /**
     * @param string|TemplateModel $model
     * @param array $vars
     * @return string
     * @throws \Throwable
     */
    function renderIf($model, array $vars = []) : string
    {
        return file_exists($template = $this->find($model)) ?
            $this->render($this->create($model, ['__template' => $template]), $vars) : '';
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
    function vars(array $vars)
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
