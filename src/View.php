<?php
/**
 *
 */

namespace View5;

use Mvc5\Model\Template;
use Mvc5\Model\ViewModel;

interface View
{
    /**
     * @param Template|ViewModel $model
     * @param array $vars
     * @return string
     */
    function render($model, array $vars = []);
}
