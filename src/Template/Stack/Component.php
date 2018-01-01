<?php
/**
 * Portions copyright (c) Taylor Otwell https://laravel.com
 * under the MIT License https://opensource.org/licenses/MIT
 */

namespace View5\Template\Stack;

use Mvc5\Exception;

trait Component
{
    /**
     * The original data passed to the component.
     *
     * @var array
     */
    protected $component = [];

    /**
     * The components being rendered.
     *
     * @var array
     */
    protected $componentStack = [];

    /**
     * The slot contents for the component.
     *
     * @var array
     */
    protected $slot = [];

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
    protected function component() : int
    {
        return count($this->componentStack) - 1;
    }

    /**
     * @return string|null
     */
    function componentName()
    {
        return array_pop($this->componentStack);
    }

    /**
     * Get the data for the current component.
     *
     * @return array
     */
    function componentVars() : array
    {
        return ['slot' => trim(ob_get_clean())] + $this->slot[$component = $this->component() + 1] + $this->component[$component];
    }

    /**
     * @return array
     */
    function endComponent()
    {
        return [
            $this->componentName() ?? Exception::invalidArgument('Cannot end a component without first starting one.'),
            $this->componentVars()
        ];
    }

    /**
     * Save the slot content for rendering.
     *
     * @return void
     */
    function endSlot()
    {
        end($this->componentStack);

        $component = $this->component();
        $slot = $this->slot($component) ?? Exception::invalidArgument('Cannot end a slot without first starting one.');

        $this->slot[$component][$slot] = trim(ob_get_clean());
    }

    /**
     * @param int $component
     * @return string|null
     * @throws \InvalidArgumentException
     */
    protected function slot(int $component)
    {
        return isset($this->slotStack[$component]) ? array_pop($this->slotStack[$component]) : null;
    }

    /**
     * Start a component rendering process.
     *
     * @param  string  $name
     * @param  array  $data
     * @return void
     */
    function startComponent(string $name, array $data = [])
    {
        if (ob_start()) {
            $this->componentStack[] = $name;

            $component = $this->component();
            $this->component[$component] = $data;
            $this->slot[$component] = [];
        }
    }

    /**
     * Start the slot rendering process.
     *
     * @param $name
     * @param array ...$args
     * @return void
     */
    function startSlot(string $name, ...$args)
    {
        $component = $this->component();

        if ($args) {
            $this->slot[$component][$name] = $args[0];
        } elseif(ob_start()) {
            $this->slot[$component][$name] = '';
            $this->slotStack[$component][] = $name;
        }
    }
}
