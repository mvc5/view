<?php
/**
 *
 */

namespace View5\Template;

use Mvc5\Model\Template;
use Mvc5\View\Template\Traverse;
use View5\Engine\ViewEngine;

trait Render
{
    /**
     *
     */
    use Section;
    use Traverse;

    /**
     * @param string $path
     * @return ViewEngine
     */
    protected abstract function engine($path);

    /**
     * @param Template $model
     * @return string
     */
    protected function output(Template $model)
    {
        return $this->start() ? $this->finish($this->engine($model->template())->render($model)) : null;
    }

    /**
     * @param string|Template $model
     * @param array $vars
     * @return mixed|null|string
     * @throws \Exception
     * @throws \Throwable
     */
    function render($model, array $vars = [])
    {
        try {

            return $this->output($this->traverse($this->template($model, $vars)));

        } catch (\Exception $exception) {

            $this->reset();

            throw $exception;

        } catch (\Throwable $exception) {

            $this->reset();

            throw $exception;
        }
    }

    /**
     * @param array|string|Template $model
     * @param array $vars
     * @return Template
     */
    protected abstract function template($model, array $vars = []);

    /**
     * @param $model
     * @param array $vars
     * @return mixed
     */
    function __invoke($model = null, array $vars = [])
    {
        return !$model instanceof Template ? $model : $this->render($model, $vars);
    }
}
