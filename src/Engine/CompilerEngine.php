<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Engine;

use View5\Compiler\Compiler;
use Mvc5\Model\Template;

class CompilerEngine
    extends PhpEngine
{
    /**
     * @var Compiler
     */
    protected $compiler;

    /**
     * @param Compiler  $compiler
     */
    function __construct(Compiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @param \Exception $e
     * @param $path
     * @throws \ErrorException
     */
    protected function error(\Exception $e, $path)
    {
        throw new \ErrorException($e->getMessage() . ' (View: ' . realpath($path) . ')', 0, 1, $e->getFile(), $e->getLine(), $e);
    }

    /**
     * @param  Template $model
     * @return string
     */
    function render(Template $model)
    {
        $path = $model->template();

        $this->compiler->expired($path)
            && $this->compiler->compile($path);

        $model->template($this->compiler->path($path));

        try {

            return parent::render($model);

        } catch(\Exception $exception) {

            $this->error($exception, $path);

        } catch(\Throwable $exception) {

            $this->error($exception, $path);

        }

        return null;
    }
}
