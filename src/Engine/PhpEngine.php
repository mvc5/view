<?php
/**
 *
 */

namespace View5\Engine;

use Exception;
use Mvc5\Model\Template;
use Mvc5\Model\ViewModel;
use Throwable;

class PhpEngine
    implements ViewEngine
{
    /**
     * @param  Template $model
     * @return string
     */
    function render(Template $model)
    {
        $render = \Closure::bind(function() {
            /** @var ViewModel $this */

            extract($this->vars(), EXTR_SKIP);

            $__ob_level__ = ob_get_level();

            ob_start();

            try {

                include $this->template();

                return ob_get_clean();

            } catch(Exception $exception) {

                while(ob_get_level() > $__ob_level__) {
                    ob_end_clean();
                }

                throw $exception;

            } catch(Throwable $exception) {

                while(ob_get_level() > $__ob_level__) {
                    ob_end_clean();
                }

                throw $exception;

            }
        },
            $model,
            $model
        );

        return $render();
    }
}
