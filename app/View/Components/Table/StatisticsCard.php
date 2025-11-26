<?php

namespace App\View\Components\Table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatisticsCard extends Component
{
    /**
     * Create a new component instance.
     */

    public $id, $title, $icon, $bgColor;

    public function __construct($id, $title, $icon, $bgColor)
    {
        //
        $this->id = $id;
        $this->title = $title;
        $this->icon = $icon;
        $this->bgColor = $bgColor;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.statistics-card');
    }
}
