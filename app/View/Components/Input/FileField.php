<?php

namespace App\View\Components\Input;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FileField extends Component
{
    /**
     * Create a new component instance.
     */

    public $id, $label, $name, $placeholder;

    public function __construct($id, $label, $name, $placeholder)
    {
        //
        $this->id = $id;
        $this->label = $label;
        $this->name = $name;
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input.file-field');
    }
}
