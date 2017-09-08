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
     * @param string $path
     * @return \Mvc5\View\ViewEngine
     */
    protected abstract function engine($path);
    /**
     * @param TemplateModel $model
     * @return string
     */
    protected function output(TemplateModel $model)
    {
        return $this->start() ? $this->finish($this->engine($model->template())->render($model)) : null;
    }

    /**
     * @param string|TemplateModel $model
     * @param array $vars
     * @return mixed|null|string
     * @throws \Exception
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
     * @param $model
     * @param array $vars
     * @return mixed
     */
    function __invoke($model = null, array $vars = [])
    {
        return !$model instanceof TemplateModel ? $model : $this->render($model, $vars);
    }
}
