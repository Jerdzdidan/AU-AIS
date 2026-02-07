@extends('layout.base')

@section('title')
Academic Progress
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
    <style>
        #pdfContent {
            display: none;
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
            margin-top: 15px;
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
            margin-bottom: 12px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
@endsection

@section('nav_title')
Academic Progress
@endsection

@section('body')
{{-- <div id="pageSpinner" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.9); z-index: 9999; display: flex; justify-content: center; align-items: center;">
    <div class="text-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 fw-bold">Loading Academic Progress...</p>
    </div>
</div> --}}

<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header 
            title="" 
            subtitle="View academic progress details"
        />
        
        <!-- Statistics Cards -->
        <div class="row mb-4">

            {{-- UNITS PROGRESS --}}
            <x-table.progress-card 
                title="Units Progress"
                icon="fa-solid fa-calculator fa-2x"
                bgColor="bg-info"
                class="col-md-4"
                numeratorId="unitsEarnedDisplay"
                denominatorId="unitsRequiredDisplay"
                progressBarId="unitsProgressBar"
                percentageId="unitsPercentage"
            />

            {{-- TOTAL Subjects --}}
            <x-table.stats-card 
                id="totalSubjects" 
                title="Total Subjects" 
                icon="fa-solid fa-file-pen fa-2x" 
                bgColor="bg-primary" 
                class="col-md-4"/>
                    
            {{-- Subject Completed --}}
            <x-table.stats-card 
                id="completedSubjects" 
                title="Subjects Completed" 
                icon="fa-solid fa-check fa-2x" 
                bgColor="bg-success" 
                class="col-md-4"/>

        </div>

        <!-- Status Filter -->
        <div class="row">
            <div class="col-md-2">
                <x-input.select-field
                    id="filter-status"
                    label="Filter by Status:"
                    icon="fa-solid fa-tags"
                    :options="[
                        ['value' => 'All', 'text' => 'All Status'],
                        ['value' => 'Complete', 'text' => 'Complete'],
                        ['value' => 'Incomplete', 'text' => 'Incomplete'],
                    ]"
                    placeholder="Select Status"
                />
            </div>
        </div>

        <!-- Year Level Tabs -->
        <div class="tab-scroll-wrapper" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <ul class="nav nav-tabs border-bottom mt-2" id="checklistYearTabs" role="tablist" style="flex-wrap: nowrap; min-width: min-content;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="year1-tab" data-bs-toggle="tab" data-bs-target="#year1" type="button" role="tab">
                        <i class="fa-solid fa-calendar me-2"></i>1st Yr.
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="year2-tab" data-bs-toggle="tab" data-bs-target="#year2" type="button" role="tab">
                        <i class="fa-solid fa-calendar me-2"></i>2nd Yr.
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="year3-tab" data-bs-toggle="tab" data-bs-target="#year3" type="button" role="tab">
                        <i class="fa-solid fa-calendar me-2"></i>3rd Yr.
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="year4-tab" data-bs-toggle="tab" data-bs-target="#year4" type="button" role="tab">
                        <i class="fa-solid fa-calendar me-2"></i>4th Yr.
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="minor-tab" data-bs-toggle="tab" data-bs-target="#minor" type="button" role="tab">
                        <i class="fa-solid fa-bookmark me-2"></i>Minor Subjects
                    </button>
                </li>
            </ul>
        </div>
        
        <!-- DataTable -->
        <x-table.table id="academicProgressTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Code</th>
            <th>Subject Name</th>
            <th>LEC</th>
            <th>LAB</th>
            <th>Status</th>
            <th>Category</th>
            <th>Year Level</th>
            <th>Semester</th>
            <th>Lec Units</th>
            <th>Lab Units</th>
            <th>Total Units</th>
            <th>Prerequisites</th>
        </x-table.table>

        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Download to PDF?</h6>
                        <small class="text-muted">Download the pdf in order to ensure a smooth enrollment process.</small>
                    </div>
                    <div>
                        <button class="btn btn-danger" id="btnDownloadPDF" disabled style="display: none;">
                            <i class="fa-solid fa-file-pdf fa-1x me-2"></i>
                            Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden PDF Content -->
        <div id="pdfContent">
            <!-- Will be populated dynamically -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/shared/generic-datatable.js') }}"></script>
<script>
let allSubjectsData = {
    year1: [],
    year2: [],
    year3: [],
    year4: [],
    minor: []
};

let statsData = {
    unitsEarned: 0,
    unitsRequired: 0,
    unitsProgress: 0,
    totalSubjects: 0,
    completedSubjects: 0
};

let studentInfo = {
    name: '{{ auth()->user()->name }}',
    studentNumber: '{{ auth()->user()->student->student_number }}',
    program: '{{ auth()->user()->student->program->name }}',
    yearLevel: '{{ auth()->user()->student->year_level }}'
};

$(document).ready(function() {

    let statsLoaded = false;
    let datatableLoaded = false;

    /// Show spinner on PDF button initially
    const $pdfButton = $('#btnDownloadPDF'); // Adjust selector to match your button
    const originalButtonText = $pdfButton.html();
    
    // Disable button and show spinner
    $pdfButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Loading...');
    $pdfButton.show();
    
    // Function to check if both are loaded
    function checkIfReady() {
        if (statsLoaded && datatableLoaded) {
            $pdfButton.prop('disabled', false).html(originalButtonText);
        }
    }

    $('#filter-status').select2({
        minimumResultsForSearch: -1,
        placeholder: 'All Status'
    });

    let selectedYearLevel = '1';

    // Initialize DataTable
    const academicProgressTable = new GenericDataTable({
        order: [[6, "asc"], [7, "asc"], [8, "asc"], [1, "asc"]],
        tableId: 'academicProgressTable',
        ajaxUrl: "{{ route('student.academic_progress.data') }}",
        ajaxData: function(d) {
            d.status = $('#filter-status').val();
            d.year_level = selectedYearLevel;
        },
        columns: [
            { data: "id", visible: false },
            { data: "subject.code" },
            { data: "subject.name" },
            { 
                data: "lecture_completed",
                responsivePriority: 1,
                render: (data, type, row) => {
                    if (!row.has_lec)
                    {
                        return '<span class="text-muted">-</span>';
                    }

                    return row.lecture_completed ? '<i class="fa-solid fa-check-circle text-success"></i>' : '<i class="fa-solid fa-times-circle text-danger"></i>';
                }
            },
            { 
                data: "laboratory_completed",
                responsivePriority: 1,
                render: (data, type, row) => {
                    if (!row.has_lab)
                    {
                        return '<span class="text-muted">-</span>';
                    }

                    return row.laboratory_completed ? '<i class="fa-solid fa-check-circle text-success"></i>' : '<i class="fa-solid fa-times-circle text-danger"></i>';
                }
            },
            {
                data: "is_completed",
                render: (data, type, row) => {
                    const status = row.is_completed ? 'Completed' : 'Incomplete';
                    const badge = row.is_completed ? 'success' : 'warning';
                    return `<span class="badge bg-label-${badge}">${status}</span>`;
                }
            },
            { data: "subject.subject_category", className: "none" },
            { 
                data: "subject.year_level",
                defaultContent: '-'
            },
            { 
                data: "subject.semester",
                defaultContent: '-'
            },
            { data: "subject.lec_units", className: "none" },
            { data: "subject.lab_units", className: "none" },
            { data: "total_units", className: "none" },
            { data: "subject.prerequisites", className: "none" },
        ],
        statsCards: {
            callback: (table) => {
                $.get("{{ route('student.academic_progress.stats') }}", (data) => {
                    $('#unitsEarnedDisplay').text(data.units_earned);
                    $('#unitsRequiredDisplay').text(data.total_units);
                    $('#unitsProgressBar').css('width', `${data.units_progress}%`).attr('aria-valuenow', data.units_progress);
                    $('#unitsPercentage').text(`${data.units_progress}%`);

                    $('#totalSubjects').text(data.total_subjects);
                    $('#completedSubjects').text(data.subjects_completed);
                    
                    // Store stats data
                    statsData = {
                        unitsEarned: data.units_earned,
                        unitsRequired: data.total_units,
                        unitsProgress: data.units_progress,
                        totalSubjects: data.total_subjects,
                        completedSubjects: data.subjects_completed
                    };

                    statsLoaded = true;
                    checkIfReady();
                }).fail((xhr) => {
                    console.error('Error fetching stats:', xhr);
                    if (xhr.status === 500) {
                        const msg = xhr.responseJSON?.message || 'Internal server error';
                        toastr.error(msg, 'Server Error');
                        return;
                    }
                    toastr.error('Error fetching statistics. Please refresh.');

                    statsLoaded = true;
                    checkIfReady();
                });
            }
        },
        initComplete: function(settings, json) {
            // Mark datatable as loaded
            datatableLoaded = true;
            checkIfReady();
        }
    }).init();

    // Load all subjects data for PDF
    function loadAllSubjectsForPDF() {
        const yearLevels = ['1', '2', '3', '4', 'minor'];
        const promises = yearLevels.map(level => {
            return $.ajax({
                url: "{{ route('student.academic_progress.data') }}",
                method: 'GET',
                data: {
                    year_level: level,
                    status: 'All'
                }
            });
        });

        Promise.all(promises).then(responses => {
            responses.forEach((response, index) => {
                const yearKey = yearLevels[index] === 'minor' ? 'minor' : `year${yearLevels[index]}`;
                allSubjectsData[yearKey] = response.data;
            });
        });
    }

    // Load data on page load
    loadAllSubjectsForPDF();

    $('#filter-status').on('change', function() {
        academicProgressTable.reload();
    });

    // Year level tab click handler
    $('#checklistYearTabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const targetTab = $(e.target).attr('id');
        
        switch(targetTab) {
            case 'year1-tab':
                selectedYearLevel = '1';
                break;
            case 'year2-tab':
                selectedYearLevel = '2';
                break;
            case 'year3-tab':
                selectedYearLevel = '3';
                break;
            case 'year4-tab':
                selectedYearLevel = '4';
                break;
            case 'minor-tab':
                selectedYearLevel = 'minor';
                break;
            default:
                selectedYearLevel = 'all';
        }
        
        academicProgressTable.table.page('first').draw('page');
    });

    // PDF Download Function
    // PDF Download Function
    $('#btnDownloadPDF').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Generating PDF...');

        // Generate PDF content
        generatePDFContent();

        setTimeout(() => {
            const element = document.getElementById('pdfContent');
            
            // Use html2canvas to capture the content
            html2canvas(element, {
                scale: 2,
                useCORS: true,
                logging: false,
                backgroundColor: '#ffffff',
                windowWidth: 800,
                onclone: function(clonedDoc) {
                    clonedDoc.getElementById('pdfContent').style.display = 'block';
                }
            }).then(canvas => {
                try {
                    const imgData = canvas.toDataURL('image/png', 1.0);
                    
                    // Create PDF with jsPDF (letter size)
                    const pdf = new jspdf.jsPDF({
                        orientation: 'portrait',
                        unit: 'mm',
                        format: 'letter',
                        compress: true
                    });
                    
                    // Get page dimensions
                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const pageHeight = pdf.internal.pageSize.getHeight();
                    
                    // Set margins
                    const margin = 10;
                    const contentWidth = pageWidth - (2 * margin);
                    const contentHeight = pageHeight - (2 * margin);
                    
                    // Calculate image dimensions
                    const canvasWidth = canvas.width;
                    const canvasHeight = canvas.height;
                    const ratio = canvasWidth / canvasHeight;
                    
                    // Fit image to page width
                    let imgWidth = contentWidth;
                    let imgHeight = imgWidth / ratio;
                    
                    // If image height exceeds page, we need multiple pages
                    if (imgHeight > contentHeight) {
                        // Calculate how many pages we need
                        const totalPages = Math.ceil(imgHeight / contentHeight);
                        
                        for (let page = 0; page < totalPages; page++) {
                            if (page > 0) {
                                pdf.addPage();
                            }
                            
                            // Calculate the portion of the image to show on this page
                            const sourceY = (canvasHeight / totalPages) * page;
                            const sourceHeight = canvasHeight / totalPages;
                            
                            // Create a temporary canvas for this page's content
                            const tempCanvas = document.createElement('canvas');
                            tempCanvas.width = canvasWidth;
                            tempCanvas.height = sourceHeight;
                            
                            const ctx = tempCanvas.getContext('2d');
                            ctx.drawImage(
                                canvas,
                                0, sourceY, canvasWidth, sourceHeight,
                                0, 0, canvasWidth, sourceHeight
                            );
                            
                            const pageImgData = tempCanvas.toDataURL('image/png', 1.0);
                            const pageImgHeight = contentWidth / ratio * (sourceHeight / canvasHeight);
                            
                            pdf.addImage(
                                pageImgData,
                                'PNG',
                                margin,
                                margin,
                                contentWidth,
                                pageImgHeight,
                                undefined,
                                'FAST'
                            );
                        }
                    } else {
                        // Single page - image fits
                        pdf.addImage(
                            imgData,
                            'PNG',
                            margin,
                            margin,
                            imgWidth,
                            imgHeight,
                            undefined,
                            'FAST'
                        );
                    }
                    
                    // Save the PDF
                    pdf.save(`Academic_Progress_${studentInfo.studentNumber}.pdf`);
                    
                    btn.prop('disabled', false).html('<i class="fa-solid fa-file-pdf fa-1x me-2"></i>Download PDF');
                    toastr.success('PDF downloaded successfully!');
                    
                } catch (error) {
                    console.error('PDF generation error:', error);
                    btn.prop('disabled', false).html('<i class="fa-solid fa-file-pdf fa-1x me-2"></i>Download PDF');
                    toastr.error('Error generating PDF: ' + error.message);
                }
            }).catch(err => {
                console.error('Canvas generation error:', err);
                btn.prop('disabled', false).html('<i class="fa-solid fa-file-pdf fa-1x me-2"></i>Download PDF');
                toastr.error('Error generating PDF. Please try again.');
            });
        }, 500);
    });

    function generatePDFContent() {
        let html = `
            <div class="pdf-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('img/logo/arellano_logo.png') }}" class="pdf-logo me-3" alt="Arellano University">
                        <div>
                            <h4 class="mb-1 fw-bold">Arellano University</h4>
                            <p class="mb-0 text-muted">Academic Progress Report</p>
                        </div>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">Generated: ${new Date().toLocaleDateString()}</small>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold mb-3">Student Information</h5>
                <div class="row">
                    <div class="col-6">
                        <p class="mb-2"><strong>Name:</strong> ${studentInfo.name}</p>
                        <p class="mb-2"><strong>Student Number:</strong> ${studentInfo.studentNumber}</p>
                        <p class="mb-2"><strong>Year Level:</strong> ${studentInfo.yearLevel}</p>
                    </div>
                    <div class="col-6">
                        <p class="mb-2"><strong>Program:</strong> ${studentInfo.program}</p>
                    </div>
                </div>
            </div>

            <div class="pdf-stats">
                <h5 class="fw-bold mb-3">Academic Progress Summary</h5>
                <div class="row">
                    <div class="col-4">
                        <div class="pdf-stats-card bg-light">
                            <h6 class="mb-1 text-primary">Units Progress</h6>
                            <p class="h4 mb-0">${statsData.unitsEarned} / ${statsData.unitsRequired}</p>
                            <small class="text-muted">${statsData.unitsProgress}% Complete</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="pdf-stats-card bg-light">
                            <h6 class="mb-1 text-primary">Total Subjects</h6>
                            <p class="h4 mb-0">${statsData.totalSubjects}</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="pdf-stats-card bg-light">
                            <h6 class="mb-1 text-success">Completed</h6>
                            <p class="h4 mb-0">${statsData.completedSubjects}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Add year sections
        const years = [
            { key: 'year1', title: '1st Year' },
            { key: 'year2', title: '2nd Year' },
            { key: 'year3', title: '3rd Year' },
            { key: 'year4', title: '4th Year' },
            { key: 'minor', title: 'Minor Subjects' }
        ];

        years.forEach(year => {
            const subjects = allSubjectsData[year.key];
            if (subjects && subjects.length > 0) {
                html += generateYearSection(year.title, subjects);
            }
        });

        $('#pdfContent').html(html);
    }

    function generateYearSection(title, subjects) {
        let html = `
            <div class="year-section">
                <div class="year-title bg-primary text-white">${title}</div>
                <table class="pdf-table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Code</th>
                            <th style="width: 30%;">Subject Name</th>
                            <th style="width: 10%;">Lec</th>
                            <th style="width: 10%;">Lab</th>
                            <th style="width: 10%;">Units</th>
                            <th style="width: 15%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        subjects.forEach(subject => {
            const lecStatus = subject.has_lec ? (subject.lecture_completed ? '<i class="fa-solid fa-check-circle text-success"></i>' : '<i class="fa-solid fa-times-circle text-danger"></i>') : '-';
            const labStatus = subject.has_lab ? (subject.laboratory_completed ? '<i class="fa-solid fa-check-circle text-success"></i>' : '<i class="fa-solid fa-times-circle text-danger"></i>') : '-';
            const status = subject.is_completed ? 'Completed' : 'Incomplete';
            const statusClass = subject.is_completed ? 'text-success' : 'text-warning';

            html += `
                <tr>
                    <td>${subject.subject.code}</td>
                    <td>${subject.subject.name}</td>
                    <td class="text-center">${lecStatus}</td>
                    <td class="text-center">${labStatus}</td>
                    <td class="text-center">${subject.total_units}</td>
                    <td class="${statusClass} fw-bold">${status}</td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
        `;

        return html;
    }
});
</script>

@endsection