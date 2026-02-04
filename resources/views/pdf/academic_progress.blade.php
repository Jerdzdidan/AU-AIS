<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Academic Progress Report</title>
    <style>
        @page {
            margin: 100px 50px 80px 50px;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }

        /* Header styles */
        .header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 80px;
            text-align: center;
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 10px;
        }

        .header-logo {
            height: 50px;
            display: inline-block;
            vertical-align: middle;
        }

        .header-text {
            display: inline-block;
            vertical-align: middle;
            margin-left: 15px;
        }

        .header-text h1 {
            margin: 0;
            font-size: 18pt;
            color: #1e3a8a;
            font-weight: bold;
        }

        .header-text p {
            margin: 2px 0;
            font-size: 9pt;
            color: #666;
        }

        /* Footer styles */
        .footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            border-top: 2px solid #e5e7eb;
            padding-top: 10px;
            font-size: 8pt;
            color: #666;
        }

        .footer .page-number:after {
            content: "Page " counter(page);
        }

        /* Content styles */
        .content {
            margin-top: 20px;
            min-height: 800px;
        }
        p, div {
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
        }

        .document-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .document-title h2 {
            color: #1e3a8a;
            font-size: 16pt;
            margin: 0 0 5px 0;
            font-weight: bold;
        }

        .document-title p {
            color: #666;
            font-size: 9pt;
            margin: 0;
        }

        /* Student info card */
        .info-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .info-card h3 {
            color: #1e3a8a;
            font-size: 11pt;
            margin: 0 0 10px 0;
            font-weight: bold;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #4b5563;
            padding: 4px 10px 4px 0;
            width: 35%;
        }

        .info-value {
            display: table-cell;
            color: #111827;
            padding: 4px 0;
        }

        /* Statistics cards */
        .stats-container {
            margin-bottom: 25px;
        }

        .stats-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .stat-card {
            display: table-cell;
            width: 33.33%;
            padding: 12px;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 6px;
            text-align: center;
        }

        .stat-card:nth-child(2) {
            background: #f0fdf4;
            border-color: #dcfce7;
        }

        .stat-card:nth-child(3) {
            background: #fef3c7;
            border-color: #fde68a;
        }

        .stat-card + .stat-card {
            border-left: none;
        }

        .stat-label {
            font-size: 8pt;
            color: #4b5563;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 20pt;
            font-weight: bold;
            color: #1e3a8a;
        }

        .stat-card:nth-child(2) .stat-value {
            color: #166534;
        }

        .stat-card:nth-child(3) .stat-value {
            color: #92400e;
        }

        /* Progress bar */
        .progress-bar-container {
            background: #e5e7eb;
            border-radius: 10px;
            height: 20px;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .progress-bar {
            background: linear-gradient(to right, #3b82f6, #1e40af);
            height: 100%;
            text-align: center;
            color: white;
            font-size: 8pt;
            line-height: 20px;
            font-weight: bold;
        }

        /* Subject tables */
        .section-title {
            color: #1e3a8a;
            font-size: 12pt;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #1e3a8a;
            font-weight: bold;
        }

        .year-title {
            color: #4b5563;
            font-size: 11pt;
            margin: 20px 0 10px 0;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            display: table-header-group; /* repeats header on each page */
        }

        tfoot {
            display: table-footer-group;
        }

        th {
            background: #1e3a8a;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9pt;
        }

        tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 7pt;
            font-weight: bold;
        }

        .status-complete {
            background: #d1fae5;
            color: #065f46;
        }

        .status-incomplete {
            background: #fee2e2;
            color: #991b1b;
        }

        .check-icon {
            color: #059669;
            font-weight: bold;
        }

        .cross-icon {
            color: #dc2626;
            font-weight: bold;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80pt;
            color: rgba(0, 0, 0, 0.03);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    {{-- <div class="watermark">ARELLANO UNIVERSITY</div> --}}

    <!-- Header -->
    <div class="header">
        <img
            src="file://{{ realpath(public_path('img/logo/arellano_logo.png')) }}"
            class="header-logo"
            alt="Arellano University Logo"
        >
        <div class="header-text">
            <h1>ARELLANO UNIVERSITY</h1>
            <p>Academic Information System</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Generated on: {{ $generatedDate }}</p>
        <p class="page-number"></p>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Document Title -->
        <div class="document-title">
            <h2>ACADEMIC PROGRESS REPORT</h2>
            <p>{{ $curriculum->program->code }} - Curriculum ({{ $curriculum->year_start }}-{{ $curriculum->year_end }})</p>
        </div>

        <!-- Student Information -->
        <div class="info-card">
            <h3>Student Information</h3>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Student Number:</div>
                    <div class="info-value">{{ $student->student_number }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $student->user->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Program:</div>
                    <div class="info-value">{{ $program->name }} ({{ $program->code }})</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Year Level:</div>
                    <div class="info-value">{{ $student->year_level }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Curriculum:</div>
                    <div class="info-value">{{ $curriculum->year_start }}-{{ $curriculum->year_end }}</div>
                </div>
            </div>
        </div>
{{--         
        <!-- Statistics -->
        <div class="stats-container">
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-label">Total Subjects</div>
                    <div class="stat-value">{{ $stats['total_subjects'] }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Completed</div>
                    <div class="stat-value">{{ $stats['completed_subjects'] }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Units Earned</div>
                    <div class="stat-value">{{ $stats['completed_units'] }}/{{ $stats['total_units'] }}</div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="progress-bar-container">
            <div class="progress-bar" style="width: {{ $stats['units_progress'] }}%">
                {{ $stats['units_progress'] }}% Complete
            </div>
        </div> --}}

        <!-- Major Subjects by Year Level -->
        <div class="section-title">MAJOR SUBJECTS</div>
        
        @foreach($groupedProgress['major'] as $yearLevel => $subjects)
            <div class="year-title">Year {{ $yearLevel }}</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%">Code</th>
                        <th style="width: 35%">Subject Name</th>
                        <th class="text-center" style="width: 10%">Semester</th>
                        <th class="text-center" style="width: 8%">Units</th>
                        <th class="text-center" style="width: 8%">LEC</th>
                        <th class="text-center" style="width: 8%">LAB</th>
                        <th class="text-center" style="width: 16%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $progress)
                    <tr>
                        <td>{{ $progress['subject']->code }}</td>
                        <td>{{ $progress['subject']->name }}</td>
                        <td class="text-center">{{ $progress['subject']->semester ?? '-' }}</td>
                        <td class="text-center">{{ $progress['total_units'] }}</td>
                        <td class="text-center">
                            @if($progress['has_lec'])
                                <span class="{{ $progress['lecture_completed'] ? 'check-icon' : 'cross-icon' }}">
                                    {{ $progress['lecture_completed'] ? '✓' : '✗' }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($progress['has_lab'])
                                <span class="{{ $progress['laboratory_completed'] ? 'check-icon' : 'cross-icon' }}">
                                    {{ $progress['laboratory_completed'] ? '✓' : '✗' }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="status-badge {{ $progress['is_completed'] ? 'status-complete' : 'status-incomplete' }}">
                                {{ $progress['is_completed'] ? 'COMPLETED' : 'INCOMPLETE' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

        <!-- Minor Subjects -->
        @if(count($groupedProgress['minor']) > 0)
            <div class="section-title" style="page-break-before: always;">MINOR SUBJECTS</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%">Code</th>
                        <th style="width: 45%">Subject Name</th>
                        <th class="text-center" style="width: 8%">Units</th>
                        <th class="text-center" style="width: 8%">LEC</th>
                        <th class="text-center" style="width: 8%">LAB</th>
                        <th class="text-center" style="width: 16%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedProgress['minor'] as $progress)
                    <tr>
                        <td>{{ $progress['subject']->code }}</td>
                        <td>{{ $progress['subject']->name }}</td>
                        <td class="text-center">{{ $progress['total_units'] }}</td>
                        <td class="text-center">
                            @if($progress['has_lec'])
                                <span class="{{ $progress['lecture_completed'] ? 'check-icon' : 'cross-icon' }}">
                                    {{ $progress['lecture_completed'] ? '✓' : '✗' }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($progress['has_lab'])
                                <span class="{{ $progress['laboratory_completed'] ? 'check-icon' : 'cross-icon' }}">
                                    {{ $progress['laboratory_completed'] ? '✓' : '✗' }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="status-badge {{ $progress['is_completed'] ? 'status-complete' : 'status-incomplete' }}">
                                {{ $progress['is_completed'] ? 'COMPLETED' : 'INCOMPLETE' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Legend -->
        <div style="margin-top: 30px; padding: 15px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
            <p style="margin: 0 0 5px 0; font-weight: bold; color: #1e3a8a;">Legend:</p>
            <p style="margin: 3px 0; font-size: 8pt;">✓ = Completed | ✗ = Not Completed | - = Not Applicable</p>
            <p style="margin: 3px 0; font-size: 8pt;">LEC = Lecture Component | LAB = Laboratory Component</p>
        </div> 

        
    </div>

    <!-- Footer Note -->
        <div style="margin-top: 20px; font-size: 8pt; color: #666; text-align: center;">
            <p>This is an official academic progress report generated by the Arellano University Academic Information System.</p>
            <p>For any discrepancies, please contact the Registrar's Office.</p>
        </div>
</body>
</html>