<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentImportRowController extends Controller
{
    // Status Constants
    const STATUS_STAGED = 'staged';
    const STATUS_COMMITTED = 'committed';

    // Validity Constants
    const VALIDITY_VALID = 'valid';
    const VALIDITY_INVALID = 'invalid';

    protected StudentImportRowValidator $validator;

    public function __construct(StudentImportRowValidator $validator)
    {
        $this->validator = $validator;
    }
}
