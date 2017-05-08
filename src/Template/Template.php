<?php
/**
 *
 */

namespace View5\Template;

use Mvc5\Template\TemplateModel;
use Mvc5\View\Template\Model;

trait Template
{
    /**
     *
     */
    use Model;

    /**
     * @param array|string|TemplateModel $model
     * @param array $vars
     * @return mixed|TemplateModel
     */
    protected function template($model, array $vars = [])
    {
        return $this->model($model, ['__env' => $this] + $vars);
    }
}