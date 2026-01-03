<?php

namespace App\View\Components\Grade;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GradeMobileTableRow extends Component
{
    /**
     * Create a new component instance.
     */

    public $subjectCode, $subjectName, $unitType, $creditUnit, $faculty, $grade;

    public function __construct($subjectCode, $subjectName, $unitType, $creditUnit, $faculty, $grade)
    {
        $this->subjectCode = $subjectCode;
        $this->subjectName = $subjectName;
        $this->unitType = $unitType;
        $this->creditUnit = $creditUnit;
        $this->faculty = $faculty;
        $this->grade = $grade;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.grade.grade-mobile-table-row');
    }
}
