<?php

namespace App\Services;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class AcademicProgressPdfGenerator
{
    protected $student;
    protected $academicProgress;
    protected $stats;

    public function __construct(Student $student)
    {
        $this->student = $student;
        $this->loadData();
    }

    protected function loadData()
    {
        // Load academic progress with subject details
        $this->academicProgress = $this->student->studentSubjectProgress()
            ->with('subject:id,code,name,lec_units,lab_units,prerequisites,subject_category,year_level,semester')
            ->get()
            ->map(function ($progress) {
                return [
                    'subject' => $progress->subject,
                    'lecture_completed' => $progress->lecture_completed,
                    'laboratory_completed' => $progress->laboratory_completed,
                    'is_completed' => $progress->isCompleted(),
                    'has_lec' => $progress->subject->lec_units > 0,
                    'has_lab' => $progress->subject->lab_units > 0,
                    'total_units' => $progress->subject->lec_units + $progress->subject->lab_units,
                ];
            });

        // Calculate statistics
        $this->calculateStats();
    }

    protected function calculateStats()
    {
        $units_completed = $this->academicProgress->sum(function ($progress) {
            if ($progress['is_completed']) {
                return ($progress['subject']->lec_units ?? 0) + ($progress['subject']->lab_units ?? 0);
            }

            return 0;
        });

        $total_units = $this->academicProgress->sum(function ($progress) {
            return ($progress['subject']->lec_units ?? 0) + ($progress['subject']->lab_units ?? 0);
        });

        $units_progress = $total_units > 0 ? $units_completed / $total_units * 100 : 0;

        $subjects_completed = $this->academicProgress->where('lecture_completed', true)
            ->where('laboratory_completed', true)
            ->count();

        $total_subjects = $this->academicProgress->count();

        $this->stats = [
            'units_earned' => $units_completed,
            'total_units' => $total_units,
            'units_progress' => round($units_progress, 2),
            'total_subjects' => $total_subjects,
            'subjects_completed' => $subjects_completed,
        ];
    }

    public function generate()
    {
        // Group subjects by year level and category
        $groupedProgress = $this->groupSubjects();

        $data = [
            'student' => $this->student,
            'program' => $this->student->program,
            'curriculum' => $this->student->curriculum,
            'stats' => $this->stats,
            'groupedProgress' => $groupedProgress,
            'generatedDate' => now()->format('F d, Y h:i A'),
        ];

        $pdf = Pdf::loadView('pdf.academic_progress', $data);
        
        // Set paper size and orientation
        $pdf->setPaper('letter', 'portrait');
        
        return $pdf;
    }

    protected function groupSubjects()
    {
        $grouped = [
            'major' => [],
            'minor' => [],
        ];

        foreach ($this->academicProgress as $progress) {
            $subject = $progress['subject'];
            $category = strtolower($subject->subject_category);

            if ($category === 'major') {
                $yearLevel = $subject->year_level ?? 'Unassigned';
                if (!isset($grouped['major'][$yearLevel])) {
                    $grouped['major'][$yearLevel] = [];
                }
                $grouped['major'][$yearLevel][] = $progress;
            } else {
                $grouped['minor'][] = $progress;
            }
        }

        // Sort major subjects by year level
        ksort($grouped['major']);

        return $grouped;
    }
    
    public function download($filename = null)
    {
        $filename = $filename ?? 'academic_progress_' . $this->student->student_number . '.pdf';
        return $this->generate()->download($filename);
    }

    public function stream()
    {
        return $this->generate()->stream();
    }
}

