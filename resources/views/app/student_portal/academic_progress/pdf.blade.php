<!DOCTYPE html>
<html
  lang="en"
  class="layout-menu-fixed layout-wide"
  data-assets-path="{{ asset('themes/sneat/assets') }}"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Academic Progress Report</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo/arellano_logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />

    <!-- Flatpicker -->
    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/libs/flatpickr/flatpickr.css') }}" />

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Remixicon icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">

    <!-- Fontawesome icons -->
    <script src="https://kit.fontawesome.com/c5804bd254.js" crossorigin="anonymous"></script>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('css/layout/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout/layout_custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout/delete_popup_modal.css') }}">

    <style>
        #pdfContent {
            background: white;
            padding: 30px;
            width: 750px;
            max-width: 100%;
            font-family: Arial, sans-serif;
        }

        #pdfContent .pdf-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #dee2e6;
        }

        #pdfContent .pdf-logo {
            width: 50px;
            height: auto;
        }

        #pdfContent .pdf-stats {
            margin: 15px 0;
        }

        #pdfContent .pdf-stats-card {
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background-color: #f8f9fa;
        }

        #pdfContent .pdf-table {
            width: 100%;
            margin-top: 0px;
            font-size: 11px;
            border-collapse: collapse;
        }

        #pdfContent .pdf-table th {
            background-color: #f8f9fa;
            padding: 8px;
            border: 1px solid #dee2e6;
            font-weight: 600;
            text-align: left;
        }

        #pdfContent .pdf-table td {
            padding: 6px 8px;
            border: 1px solid #dee2e6;
        }

        #pdfContent .year-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        #pdfContent .year-title {
            color: white;
            padding: 8px 12px;
            margin-bottom: 0px!important;
            border-radius: 4px 4px 0 0;
            font-weight: 600;
            font-size: 14px;
        }

        /* Spinner overlay (outside capture so it won't appear in PDF) */
        #spinnerOverlay{
            position:fixed;
            inset:0;
            display:flex;
            align-items:center;
            justify-content:center;
            flex-direction:column;
            gap:12px;
            background:rgb(255, 255, 255);
            z-index:9999;
            text-align:center;
            padding:16px;
        }
        #spinnerOverlay .loader{
            width:48px;
            height:48px;
            border:6px solid #e9ecef;
            border-top-color:#0d6efd;
            border-radius:50%;
            animation:spin .8s linear infinite;
        }
        @keyframes spin{to{transform:rotate(360deg)}}
    </style>

    <!-- Helpers -->
    <script src="{{ asset('themes/sneat/assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('themes/sneat/assets/js/config.js') }}"></script>
</head>
<body>
    {{-- <div id="spinnerOverlay" aria-hidden="true" class="text-center">
        <div class="loader" role="status" aria-hidden="true"></div>
        <p class="text-center" style="margin-left:5px!important; font-weight:600; color:#333;">Downloading PDF...</p>
    </div> --}}

    <div class="pdf-wrapper" id="pdfContent">
        <!-- Header -->
        <div class="pdf-header mb-1">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('img/logo/arellano_logo.png') }}" class="pdf-logo me-3" alt="Arellano University">
                    <div>
                        <h4 class="mb-1 fw-bold">Arellano University</h4>
                        <p class="mb-0 text-muted">Academic Progress Report</p>
                    </div>
                </div>
                <div class="text-end">
                    <small class="text-muted">Generated: {{ now()->format('M d, Y') }}</small>
                </div>
            </div>
        </div>

        <!-- Student Information -->
        <div class="mb-0 mt-0">
            <h5 class="fw-bold mb-3">Student Information</h5>
            <div class="row">
                <div class="col-6">
                    <p class="mb-2"><strong>Name:</strong> {{ $user->name }}</p>
                    <p class="mb-2"><strong>Student Number:</strong> {{ $student->student_number }}</p>
                    <p class="mb-2"><strong>Year Level:</strong> {{ $student->year_level }}</p>
                </div>
                <div class="col-6">
                    <p class="mb-2"><strong>Program:</strong> {{ $student->program->code ?? 'N/A' }} <small class="text-muted">({{ $student->program->name ?? 'N/A' }})</small></p>
                </div>
            </div>
        </div>

        <!-- Academic Progress Summary -->
        <div class="row mb-0">
            <div class="col-md-4 my-2 my-md-0">
                <div class="card bg-info h-100 text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0 text-white fw-bold">Units Progress</h6>
                                <p class="mb-0 text-white mt-2">
                                    <span id="unitsEarnedDisplay">{{ $stats['units_earned'] }}</span>/<span id="unitsRequiredDisplay">{{ $stats['total_units'] }}</span>
                                </p>
                                <div class="progress mt-2" style="height: 8px;">
                                    <div id="unitsProgressBar" class="progress-bar" role="progressbar"
                                            style="width: {{ $stats['units_progress'] }}%" aria-valuenow="{{ $stats['units_progress'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-white mt-1" id="unitsPercentage">{{ $stats['units_progress'] }}%</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-book fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 my-2 my-md-0">
                <div class="card bg-primary h-100 text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0 text-white fw-bold">Total <br> Subjects</h6>
                                <h6 class="mb-0 text-white">{{ $stats['total_subjects'] }}</h6>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-file-pen fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 my-2 my-md-0">
                <div class="card bg-success h-100 text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0 text-white fw-bold">Subjects Completed</h6>
                                <h6 class="mb-0 text-white">{{ $stats['subjects_completed'] }}</h6>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-file-pen fa-2x"></i>
                            </div>
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
                    <div class="year-title bg-primary text-white mb-0 pt-1 pb-1">{{ $yearTitles[$yearKey] }}</div>
                    <div class="table-responsive">
                        <table class="pdf-table">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">Code</th>
                                    <th style="width: 40%;">Subject Name</th>
                                    <th style="width: 5%;">Lec</th>
                                    <th style="width: 5%;">Lab</th>
                                    <th style="width: 5%;">Units</th>
                                    <th style="width: 5%;">Grade</th>
                                    <th style="width: 15%;">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($years[$yearKey] as $progress)
                                    @php
                                        $hasLec = $progress->subject->lec_units > 0;
                                        $hasLab = $progress->subject->lab_units > 0;
                                        $lecUnits = (int) $progress->subject->lec_units;
                                        $labUnits = (int) $progress->subject->lab_units;

                                        if ($progress->grade == "INC") {
                                            $gradeClass = 'text-warning';
                                        }
                                        else if ($progress->grade == "DRP") {
                                            $gradeClass = 'text-dark';
                                        }
                                        else if ($progress->grade >= 1 && $progress->grade <= 3) {
                                            $gradeClass = 'text-success';
                                        }
                                        else if ($progress->grade == 5) {
                                            $gradeClass = 'text-danger';
                                        }
                                        else if ($progress->grade == null)
                                        {
                                            $gradeClass = '';
                                        }
                                        else {
                                            $gradeClass = 'text-danger';
                                        }

                                        $remarksClass = '';
                                        $remark = '-';
                                        if ($progress->remarks == "DROPPED") {
                                            $remark = 'Dropped';
                                            $remarksClass = 'text-dark~';
                                        }
                                        else if ($progress->remarks == "INCOMPLETE") {
                                            $remark = 'Incomplete';
                                            $remarksClass = 'text-warning';
                                        }
                                        else if ($progress->remarks == "FAILED") {
                                            $remark = 'Failed';
                                            $remarksClass = 'text-danger';
                                        }
                                        else if ($progress->remarks == "COMPLETED") {
                                            $remark = 'Completed';
                                            $remarksClass = 'text-success';
                                        }

                                        if ($hasLec && $hasLab) {
                                            $units = $lecUnits + $labUnits;
                                        } else if ($hasLec) {
                                            $units = $lecUnits;
                                        } else if ($hasLab) {
                                            $units = $labUnits;
                                        } else {
                                            $units = 0;
                                        }

                                        $units = (int) $units;
                                    @endphp

                                    <tr>
                                        <td style="padding: 3px;">{{ $progress->subject->code }}</td>
                                        <td style="padding: 3px;">{{ $progress->subject->name }}</td>
                                        <td style="padding: 3px;" class="text-center">{{ $lecUnits }}</td>
                                        <td style="padding: 3px;" class="text-center">{{ $labUnits }}</td>
                                        <td style="padding: 3px;" class="text-center">{{ $units }}</td>
                                        <td style="padding: 3px;" class="text-center {{ $gradeClass }}">{{ $progress->grade ? $progress->grade : '-' }}</td>
                                        <td style="padding: 3px;" class="text-center {{ $remarksClass }}">{{ $remark }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Core JS -->

    <script src="{{ asset('themes/sneat/assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('themes/sneat/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('themes/sneat/assets/vendor/js/bootstrap.js') }}"></script>

    <script src="{{ asset('themes/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('themes/sneat/assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('themes/sneat/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    <!-- Flatpicker -->
    <script src="{{ asset('themes/sneat/assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- html2pdf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Main JS -->

    <script src="{{ asset('themes/sneat/assets/js/main.js') }}"></script>

    <!-- Page JS -->
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

            // Generate and save PDF, then close the window. The element must
            // remain visible for html2canvas/html2pdf to capture correctly.
            const task = html2pdf().set(opt).from(element).save();

            // html2pdf returns a Promise-like object in most builds; handle both
            if (task && typeof task.then === 'function') {
                task.then(() => setTimeout(() => window.close(), 500)).catch(() => setTimeout(() => window.close(), 1500));
            } else {
                // Fallback: close after small delay to allow download dialog
                setTimeout(() => window.close(), 1500);
            }
        });
    </script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- html2pdf script -->

</body>
</html>
