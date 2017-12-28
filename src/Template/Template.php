<?php
/**
 *
 */

namespace View5\Template;

use Mvc5\Template\TemplateModel;
use Mvc5\View\Template\Model;
use Mvc5\View\Template\Traverse;

trait Template
{
    /**
     *
     */
    use Model;
    use Traverse;

    /**
     * @param array|string|TemplateModel $model
     * @param array $vars
     * @return TemplateModel
     */
    protected function template($model, array $vars = []) : TemplateModel
    {
        return $this->model($model, ['__env' => $this] + $vars);
    }
}
