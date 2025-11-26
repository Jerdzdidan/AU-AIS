<?php

namespace App\View\Components\Modals;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserCreationAndUpdateModal extends Component
{
    /**
     * Create a new component instance.
     */

    public $id, $title, $action, $submitButtonName;

    public function __construct($id, $title, $action, $submitButtonName)
    {
        //
        $this->id = $id;
        $this->title = $title;
        $this->action = $action;
        $this->submitButtonName = $submitButtonName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modals.creation-and-update-modal');
    }
}
