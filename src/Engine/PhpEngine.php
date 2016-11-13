<?php
/**
 *
 */

namespace View5\Engine;

use Mvc5\Model\Template;
use Mvc5\View\Template\Output;

class PhpEngine
    implements ViewEngine
{
    /**
     *
     */
    use Output;

    /**
     * @param  Template $model
     * @return string
     */
    function render(Template $model)
    {
        return $this->output($model);
    }
}
