<?php
/**
 *
 */

namespace View5;

use Mvc5\Exception;
use Mvc5\Template\TemplateModel;
use Mvc5\View\ViewEngine;
use Throwable;

use function ltrim;
use function Mvc5\Template\render;
use function realpath;
use function substr;
use function strlen;

final class Engine
    implements ViewEngine
{
    /**
     * @var callable
     */
    protected $compiler;

    /**
     * @var string
     */
    protected string $extension;

    /**
     * @var File
     */
    protected File $file;

    /**
     * @param File $file
     * @param callable $compiler
     * @param string $extension
     */
    function __construct(File $file, callable $compiler, string $extension = 'blade.php')
    {
        $this->compiler = $compiler;
        $this->extension = $extension;
        $this->file = $file;
    }

    /**
     * @param string $path
     * @return bool
     */
    protected function match(string $path) : bool
    {
        return substr($path, -strlen('.' . $this->extension)) === '.' . $this->extension;
    }

    /**
     * @param TemplateModel $model
     * @param string $template
     * @param string $path
     * @return TemplateModel
     */
    protected function model(TemplateModel $model, string $template, string $path) : TemplateModel
    {
        $this->file->expired($template, $path)
            && $this->file->write($path, ($this->compiler)($this->file->read($template)));

        return $model->withTemplate($path);
    }

    /**
     * @param TemplateModel $model
     * @return string
     * @throws Throwable
     */
    function render(TemplateModel $model) : string
    {
        return ltrim($this->match($model->template()) ? $this->template($model, $model->template()) : render($model));
    }

    /**
     * @param TemplateModel $model
     * @param string $template
     * @return string
     * @throws Throwable
     */
    protected function template(TemplateModel $model, string $template) : string
    {
        try {

            return render($this->model($model, $template, $this->file->path($template)));

        } catch(Throwable $exception) {
            return Exception::errorException(
                $exception->getMessage() . ' (View: ' . realpath($template) . ')',
                0, E_ERROR, $exception->getFile(), $exception->getLine(), $exception
            );
        }
    }

    /**
     * @param TemplateModel $model
     * @return string
     * @throws Throwable
     */
    function __invoke(TemplateModel $model) : string
    {
        return $this->render($model);
    }
}
