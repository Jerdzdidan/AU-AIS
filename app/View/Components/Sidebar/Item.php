<?php

namespace App\View\Components\Sidebar;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Item extends Component
{
    /**
     * Create a new component instance.
     */
    public $route, $name, $icon, $param;

    public function __construct($route = '', $name = '', $icon = '', $param = '')
    {
        $this->route = $route;
        $this->name = $name;
        $this->icon = $icon;
        $this->param = $param;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar.item');
    }
}
