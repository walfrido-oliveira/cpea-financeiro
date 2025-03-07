<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CustomSelect extends Component
{

    public $options;
    public $value;
    public $ids;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($options, $value, $ids = null)
    {
        $this->options = $options;
        $this->value = $value;
        $this->ids = $ids;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.custom-select');
    }
}
