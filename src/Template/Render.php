<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template;

use Mvc5\Model;
use Mvc5\Model\Template;
use Mvc5\Model\ViewModel;
use Mvc5\Plugin;
use Mvc5\Signal;

trait Render
{
    /**
     *
     */
    use Plugin;
    use Signal;

    /**
     * @var string
     */
    protected $model = Model::class;

    /**
     * @var callable|null
     */
    protected $provider;

    /**
     * @param $path
     * @return mixed
     */
    abstract function engine($path);

    /**
     * Get the content of the view instance.
     *
     * @param Template $model
     * @return string
     */
    protected function content(Template $model)
    {
        // We will keep track of the amount of views being rendered so we can flush
        // the section after the complete rendering operation is done. This will
        // clear out the sections for any separate views that may be rendered.
        $this->incrementRender();

        $content = $this->engine($model->template())->render($model);

        // Once we've finished rendering the view, we'll decrement the render count
        // so that each sections get flushed out next time a view is created and
        // no old sections are staying around in the memory of an environment.
        $this->decrementRender();

        return $content;
    }

    /**
     * @param string $model
     * @return callable|mixed|null|object
     */
    protected function create($model)
    {
        return ($this->provider ? $this->signal($this->provider, [$model]) : null) ? : new $this->model($model);
    }

    /**
     * @param string|Template $model
     * @param array $vars
     * @return Template
     */
    protected function model($model, array $vars = [])
    {
        !$model instanceof Template
            && $model = $this->create($model);

        $vars && $model->vars($vars);

        foreach($model as $k => $v) {
            $v instanceof Template && $model[$k] = $this->render($v);
        }

        ($template = $model->template()) && false === strpos($template, '.')
            && $model->template($this->find($template));

        $model instanceof ViewModel && !$model->service()
            && $model->service($this->service());

        $model->vars(['__env' => $this]);

        return $model;
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

            $contents = $this->content($this->model($model, $vars));

            // Once we have the contents of the view, we will flush the sections if we are
            // done rendering all views so that there is nothing left hanging over when
            // another view gets rendered in the future by the application developer.
            $this->flushSectionsIfDoneRendering();

            return $contents;

        } catch (\Exception $exception) {

            $this->flushSections();

            throw $exception;

        } catch (\Throwable $exception) {

            $this->flushSections();

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
        return !$model instanceof Template ? $model : $this->render($model, $vars);
    }
}
