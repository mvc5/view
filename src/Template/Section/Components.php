<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template\Section;

trait Components
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
        return array_merge(
            $this->componentData[$count = count($this->componentStack)],
            ['slot' => trim(ob_get_clean())], $this->slots[$count]
        );
    }

    /**
     * Save the slot content for rendering.
     *
     * @return void
     */
    function endSlot()
    {
        end($this->componentStack);

        $currentSlot = array_pop($this->slotStack[$this->currentComponent()]);

        $this->slots[$this->currentComponent()][$currentSlot] = trim(ob_get_clean());
    }

    /**
     * Render the current component.
     *
     * @return string
     */
    function renderComponent()
    {
        $name = array_pop($this->componentStack);

        return $this->render($name, $this->componentData($name));
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
        if (count(func_get_args()) == 2) {
            $this->slots[$this->currentComponent()][$name] = $content;
        } else {
            if (ob_start()) {
                $this->slots[$this->currentComponent()][$name] = '';

                $this->slotStack[$this->currentComponent()][] = $name;
            }
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

            $this->componentData[$this->currentComponent()] = $data;

            $this->slots[$this->currentComponent()] = [];
        }
    }
}
