<?php
/**
 *
 */

namespace View5\Engine;

use Mvc5\Model\Template;

interface ViewEngine
{
    /**
     * @param  Template $model
     * @return string
     */
    function render(Template $model);
}
