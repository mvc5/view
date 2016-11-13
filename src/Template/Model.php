<?php
/**
 *
 */

namespace View5\Template;

use Mvc5\Model\Template;
use Mvc5\Signal;
use Mvc5\View\Template\Model as _Model;

trait Model
{
    /**
     *
     */
    use _Model;
    use Signal;

    /**
     * @var callable|null
     */
    protected $provider;

    /**
     * @param string $model
     * @return callable|mixed|null|object
     */
    protected function create($model)
    {
        return ($this->provider ? $this->signal($this->provider, [$model]) : null) ? : new $this->model($model);
    }

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
