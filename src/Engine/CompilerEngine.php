<?php
/**
 *
 */

namespace View5\Engine;

use Mvc5\Exception;
use Mvc5\Model\Template;
use Mvc5\View\Template\Output;
use View5\Compiler\Compiler;

class CompilerEngine
    implements ViewEngine
{
    /**
     *
     */
    use Output;
    use Storage;

    /**
     * @var Compiler
     */
    protected $compiler;

    /**
     * @param Compiler $compiler
     * @param array $options
     */
    function __construct(Compiler $compiler, array $options = [])
    {
        $this->compiler = $compiler;

        isset($options['directory']) &&
            $this->directory = $options['directory'];

        isset($options['extension']) &&
            $this->extension = $options['extension'];
    }

    /**
     * @param string $template
     * @param string $path
     * @return void
     */
    protected function compile($template, $path)
    {
        $this->store($path, $this->compiler->compile($this->read($template)));
    }

    /**
     * @param Template $model
     * @param $template
     * @param $path
     * @return Template
     */
    protected function model(Template $model, $template, $path)
    {
        $this->expired($template, $path)
            && $this->compile($template, $path);

        $model->template($path);

        return $model;
    }

    /**
     * @param  Template $model
     * @return string
     */
    function render(Template $model)
    {
        return $this->template($model, $model->template());
    }

    /**
     * @param $model
     * @param $template
     * @return null|string
     * @throws \ErrorException
     */
    protected function template($model, $template)
    {
        try {

            return $this->output($this->model($model, $template, $this->path($template)));

        } catch(\Exception $exception) {} catch(\Throwable $exception) {}

        return Exception::errorException(
            $exception->getMessage() . ' (View: ' . realpath($template) . ')', 0, E_ERROR, $exception->getFile(), $exception->getLine(), $exception
        );
    }
}
