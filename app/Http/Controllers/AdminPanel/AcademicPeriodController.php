<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class AcademicPeriodController extends Controller
{
    //
    public function index()
    {
        return view('app.admin_panel.academic_period_management.index');
    }

    public function getData() {
        $academicPeriods = AcademicPeriod::get();

        return datatables()->of($academicPeriods)
            ->editColumn('id', function ($row) {
                return Crypt::encryptString($row->id);
            })
            ->make(true);
    }

    public function getStats() {
        $current = AcademicPeriod::where('is_current', true)->first();


        return response()->json([
            'total' => AcademicPeriod::count(),
            'current' => $current->name,
        ]);
    }

    public function store(Request $request) {
        $academicPeriods = AcademicPeriod::get();

        $validated = $request->validate([
            'year_start' => 'required|integer',
            'year_end' => 'required|integer|gt:year_start',
            'semester' => 'required|string|max:255',
        ]);

        $errors = [];

        if(str($validated['year_start'])->length() != 4) {
            $errors['year_start'] = ['The start year must be a 4-digit year.'];
        }
        if(str($validated['year_end'])->length() != 4) {
            $errors['year_end'] = ['The end year must be a 4-digit year.'];
        }

        if ($validated['year_start'] >= $validated['year_end']) {
            $errors['year_start'] = ['The start year must be less than the end year.'];
        }
        if (abs($validated['year_start']) - abs($validated['year_end']) != -1) {
            $errors['year_end'] = ['The end year must be exactly one year after the start year.'];
        }

        $validated['school_year'] = $validated['year_start'] . '-' . $validated['year_end'];

        if ($academicPeriods->where('school_year', $validated['school_year'])->where('semester', $validated['semester'])->count() > 0) {
            $errors['semester'] = ['The semester already exists for the specified school year.'];
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        $name = 'A.Y. ' . $validated['school_year'] . ' - ' . $validated['semester'];

        if ($validated['semester'] != 'SUMMER') {
            $name .= ' Semester';
        }

        AcademicPeriod::create([
            'name' => $name,
            'school_year' => $validated['school_year'],
            'year_start' => $validated['year_start'],
            'year_end' => $validated['year_end'],
            'semester' => $validated['semester'],
        ]);
        return response()->json(['success' => true]);
    }

    public function edit($id) 
    {
        $decrypted = Crypt::decryptString($id);

        $academicPeriod = AcademicPeriod::findOrFail($decrypted);

        return response()->json([
            'id' => Crypt::encryptString($academicPeriod->id),
            'name' => $academicPeriod->name,
            'school_year' => $academicPeriod->school_year,
            'year_start' => $academicPeriod->year_start,
            'year_end' => $academicPeriod->year_end,
            'semester' => $academicPeriod->semester,
        ]);
    }

    public function update(Request $request, $id)
    {
        $decrypted = Crypt::decryptString($id);
        $academicPeriod = AcademicPeriod::findOrFail($decrypted);

        $validated = $request->validate([
            'year_start' => 'required|integer|min:4|max:4',
            'year_end' => 'required|integer|max:4|gt:year_start',
            'semester' => 'required|string|max:255',
        ]);

        $errors = [];

        if ($validated['year_start'] >= $validated['year_end']) {
            $errors['year_start'] = ['The start year must be less than the end year.'];
        }
        if (abs($validated['year_start']) - abs($validated['year_end']) != -1) {
            $errors['year_end'] = ['The end year must be exactly one year after the start year.'];
        }

        $validated['school_year'] = $validated['year_start'] . '-' . $validated['year_end'];

        if (AcademicPeriod::where('id', '!=', $academicPeriod->id)->where('school_year', $validated['school_year'])->where('semester', $validated['semester'])->count() > 0) {
            $errors['semester'] = ['The semester already exists for the specified school year.'];
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        $validated['name'] = 'A.Y. ' . $validated['school_year'] . ' - ' . $validated['semester'];

        if ($validated['semester'] != 'SUMMER') {
            $validated['name'] .= ' Semester';
        }

        $academicPeriod->name = $validated['name'];
        $academicPeriod->school_year = $validated['school_year'];
        $academicPeriod->semester = $validated['semester'];
        $academicPeriod->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $decrypted = Crypt::decryptString($id);
        AcademicPeriod::findOrFail($decrypted)->delete();
                     
        return response()->json([
            'success' => true,
            'message' => 'Academic Period deleted successfully.'
        ]);
    }

    public function toggle($id)
    {
        $decrypted = Crypt::decryptString($id);
        $academicPeriod = AcademicPeriod::findOrFail($decrypted);

        AcademicPeriod::where('is_current', true)->update(['is_current' => false]);

        $academicPeriod->is_current = true;
        $academicPeriod->save();

        return response()->json([
            'success' => true,
            'message' => 'Academic Period status updated successfully.'
        ]);
    }

    public function getAcademicPeriodsForSelect(Request $request) {
        $query = AcademicPeriod::query();

        if ($search = $request->input('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $academicPeriods = $query->get(['id', 'name']);

        $formatted = $academicPeriods->map(function ($data) {
            return [
                'id' => $data->id,
                'name' => $data->name,
            ];
        });

        return response()->json($formatted);
    }
}
