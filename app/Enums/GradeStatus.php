<?php

namespace App\Enums;

enum GradeStatus: string
{
    case FAILED = "failed";
    case INCOMPLETE = "incomplete";
    case DROPPED = "dropped";
    case COMPLETED = "completed";
}
