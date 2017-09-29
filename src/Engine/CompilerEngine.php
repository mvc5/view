<?php
/**
 *
 */

namespace View5\Engine;

use Mvc5\Exception;
use Mvc5\Template\TemplateModel;
use Mvc5\View\Engine\PhpEngine;
use View5\Compiler\Compiler;

class CompilerEngine
    extends PhpEngine
{
    /**
     *
     */
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

        isset($options['expired']) &&
            $this->expired = (bool) $options['expired'];

        isset($options['directory']) &&
            $this->directory = $options['directory'];

        isset($options['extension']) &&
            $this->extension = $options['extension'];
    }

    /**
     * @param string $template
     * @param string $path
     * @return bool
     */
    protected function compile($template, $path) : bool
    {
        return $this->store($path, $this->compiler->compile($this->read($template)));
    }

    /**
     * @param TemplateModel $model
     * @param string $template
     * @param string $path
     * @return TemplateModel
     */
    protected function model(TemplateModel $model, string $template, string $path) : TemplateModel
    {
        $this->expired($template, $path)
            && $this->compile($template, $path);

        return $model->withTemplate($path);
    }

    /**
     * @param  TemplateModel $model
     * @return string
     */
    function render(TemplateModel $model) : string
    {
        return $this->template($model, $model->template());
    }

    /**
     * @param TemplateModel $model
     * @param string $template
     * @return string
     * @throws \ErrorException
     */
    protected function template(TemplateModel $model, string $template) : string
    {
        try {

            return parent::render($this->model($model, $template, $this->path($template)));

        } catch(\Throwable $exception) {
            return Exception::errorException(
                $exception->getMessage() . ' (View: ' . realpath($template) . ')',
                0, E_ERROR, $exception->getFile(), $exception->getLine(), $exception
            );
        }
    }
}
