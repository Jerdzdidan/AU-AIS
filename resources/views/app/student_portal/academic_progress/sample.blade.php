<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Progress Report - {{ $student->student_number }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/css/demo.css') }}" />
    
    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/c5804bd254.js" crossorigin="anonymous"></script>
    
    <style>
        @page {
            size: 8.5in 13in;
            margin: 0.5in;
        }

        body {
            background: white !important;
        }

        .pdf-wrapper {
            background: white;
            padding: 30px;
            max-width: 8.5in;
            margin: 0 auto;
        }

        .pdf-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .pdf-header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .pdf-header-left img {
            width: 50px;
            height: auto;
        }

        .pdf-header-left h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .pdf-header-left small {
            display: block;
            font-size: 0.875rem;
        }

        .pdf-header-right {
            text-align: right;
            font-size: 0.875rem;
            color: #6c757d;
        }

    </style>
</head>
<body>
    <div class="pdf-wrapper" id="pdfContent">
        <!-- Header -->
        <div class="pdf-header mb-4 pb-3">
            <div class="pdf-header-left">
                <img src="{{ asset('img/logo/arellano_logo.png') }}" alt="Arellano University">
                <div>
                    <h3 class="mb-0">Arellano University</h3>
                    <small class="text-muted">Academic Progress Report</small>
                </div>
            </div>
            <div class="pdf-header-right">
                <small>Generated: {{ now()->format('M d, Y') }}</small>
            </div>
        </div>

        <!-- Student Information -->
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-user-line me-2"></i>Student Information
                </h6>
                <div class="row">
                    <div class="col-6 mb-2">
                        <small class="fw-6">Name:</small><br>
                        <small>{{ $user->name }}</small>
                    </div>
                    <div class="col-6 mb-2">
                        <small class="fw-6">Student Number:</small><br>
                        <small>{{ $student->student_number }}</small>
                    </div>
                    <div class="col-6">
                        <small class="fw-6">Program:</small><br>
                        <small>{{ $student->program->name ?? 'N/A' }}</small>
                    </div>
                    <div class="col-6">
                        <small class="fw-6">Year Level:</small><br>
                        <small>{{ $student->year_level }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Progress Summary -->
        <div class="mb-4">
            <h6 class="card-title">
                <i class="ri-bar-chart-line me-2"></i>Academic Progress Summary
            </h6>
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <small class="text-muted text-uppercase fw-6 d-block mb-2">Units Progress</small>
                            <h5 class="mb-1">{{ $stats['units_earned'] }}/{{ $stats['total_units'] }}</h5>
                            <small class="text-muted">{{ $stats['units_progress'] }}% Complete</small>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <small class="text-muted text-uppercase fw-6 d-block mb-2">Total Subjects</small>
                            <h5 class="mb-1">{{ $stats['total_subjects'] }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <small class="text-muted text-uppercase fw-6 d-block mb-2">Completed</small>
                            <h5 class="mb-1">{{ $stats['subjects_completed'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Year Sections -->
        @php
            $yearTitles = [
                '1' => '1st Year',
                '2' => '2nd Year',
                '3' => '3rd Year',
                '4' => '4th Year',
                'minor' => 'Minor Subjects'
            ];
            $yearClasses = [
                '1' => 'year1',
                '2' => 'year2',
                '3' => 'year3',
                '4' => 'year4',
                'minor' => 'minor'
            ];
        @endphp

        @foreach(['1', '2', '3', '4', 'minor'] as $yearKey)
            @if($years[$yearKey])
                <div class="year-section mb-4">
                    <div class="year-title {{ $yearClasses[$yearKey] }}">
                        <i class="ri-book-line me-2"></i>{{ $yearTitles[$yearKey] }}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 12%;">Code</th>
                                    <th style="width: 40%;">Subject Name</th>
                                    <th style="width: 8%; text-align: center;">Lec</th>
                                    <th style="width: 8%; text-align: center;">Lab</th>
                                    <th style="width: 8%; text-align: center;">Units</th>
                                    <th style="width: 24%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($years[$yearKey] as $progress)
                                    <tr>
                                        <td class="fw-6">{{ $progress->subject->code }}</td>
                                        <td>{{ $progress->subject->name }}</td>
                                        <td style="text-align: center;">
                                            @if($progress->subject->lec_units > 0)
                                                <span class="{{ $progress->lecture_completed ? 'text-success' : 'text-danger' }} fw-bold">
                                                    {{ $progress->lecture_completed ? '✓' : '✗' }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if($progress->subject->lab_units > 0)
                                                <span class="{{ $progress->laboratory_completed ? 'text-success' : 'text-danger' }} fw-bold">
                                                    {{ $progress->laboratory_completed ? '✓' : '✗' }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">{{ ($progress->subject->lec_units ?? 0) + ($progress->subject->lab_units ?? 0) }}</td>
                                        <td>
                                            @if($progress->isCompleted())
                                                <span class="badge bg-success">Completed</span>
                                            @else
                                                <span class="badge bg-warning">Incomplete</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- html2pdf script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('pdfContent');
            const filename = `Academic_Progress_{{ $student->student_number }}.pdf`;

            const opt = {
                margin: 10,
                filename: filename,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { format: [215.9, 330.2], unit: 'mm', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save();
        });
    </script>
</body>
</html>
