<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template\Stack;

trait Component
{
    /**
     * The components being rendered.
     *
     * @var array
     */
    protected $componentStack = [];

    /**
     * The original data passed to the component.
     *
     * @var array
     */
    protected $componentData = [];

    /**
     * The slot contents for the component.
     *
     * @var array
     */
    protected $slots = [];

    /**
     * The names of the slots being rendered.
     *
     * @var array
     */
    protected $slotStack = [];

    /**
     * Get the index for the current component.
     *
     * @return int
     */
    protected function currentComponent()
    {
        return count($this->componentStack) - 1;
    }

    /**
     * Get the data for the given component.
     *
     * @param  string  $name
     * @return array
     */
    protected function componentData($name)
    {
        return ['slot' => trim(ob_get_clean())] + $this->slots[$slot = count($this->componentStack)] + $this->componentData[$slot];
    }

    /**
     * Save the slot content for rendering.
     *
     * @return void
     */
    function endSlot()
    {
        end($this->componentStack);

        $this->slots[$current = $this->currentComponent()][array_pop($this->slotStack[$current])] = trim(ob_get_clean());
    }

    /**
     * Render the current component.
     *
     * @return string
     */
    function renderComponent()
    {
        return $this->render($name = array_pop($this->componentStack), $this->componentData($name));
    }

    /**
     * Start the slot rendering process.
     *
     * @param  string  $name
     * @param  string|null  $content
     * @return void
     */
    function slot($name, $content = null)
    {
        $current = $this->currentComponent();

        if (count(func_get_args()) == 2) {
            $this->slots[$current][$name] = $content;
        } elseif(ob_start()) {
            $this->slots[$current][$name] = '';
            $this->slotStack[$current][] = $name;
        }
    }

    /**
     * Start a component rendering process.
     *
     * @param  string  $name
     * @param  array  $data
     * @return void
     */
    function startComponent($name, array $data = [])
    {
        if (ob_start()) {
            $this->componentStack[] = $name;

            $current = $this->currentComponent();
            $this->componentData[$current] = $data;
            $this->slots[$current] = [];
        }
    }
}
