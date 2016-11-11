<?php
/**
 *
 */

namespace View5;

use Mvc5\Model\Template;

class Renderer
{
    /**
     * @var View
     */
    protected $view;

    /**
     * @param View $view
     */
    function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param string|Template $template
     * @param array $vars
     * @return string
     */
    function __invoke($template, array $vars = [])
    {
        return $this->view->render($template, $vars);
    }
}
