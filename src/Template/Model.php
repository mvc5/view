<?php
/**
 *
 */

namespace View5\Template;

use Mvc5\Model\Template;
use Mvc5\View\Template\Model as _Model;

trait Model
{
    /**
     *
     */
    use _Model;

    /**
     * @param array|string|Template $model
     * @param array $vars
     * @return Template
     */
    protected function template($model, array $vars = [])
    {
        return $this->model($model, ['__env' => $this] + $vars);
    }
}
