@extends('layout.base')

@section('title')
Student Academic Progress
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
Student Academic Progress
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header
            title=""
            subtitle=""
            showBackButton="true"
            backUrl="{{ route('officer.students') }}">
        </x-table.page-header>

        <!-- Student Information -->
        <div class="card border-0 shadow-sm mt-0 mb-4" style="margin-top: -15px!important">
            <div class="card-body">
                <h5 class="fw-bold mb-4 text-primary">
                    <i class="fa-solid fa-user-graduate me-2"></i>Student Information
                </h5>

                <div class="row g-3"> <div class="col-md-6">
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-semibold text-muted">Full Name:</div>

                            <div class="col-sm-8 text-dark">{{ $student->user->name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-semibold text-muted">Student No.:</div>
                            <div class="col-sm-8 font-monospace">{{ $student->student_number }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-semibold text-muted">Year Level:</div>
                            <div class="col-sm-8">
                                <span class="badge bg-outline-primary text-white fw-bold">
                                Yr. {{ $student->year_level }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 border-start-md ps-md-4">
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-semibold text-muted">Program:</div>
                            <div class="col-sm-8">
                                <div class="fw-bold">{{ $student->program->code ?? 'N/A' }}</div>
                                <small class="text-muted d-block">{{ $student->program->name ?? 'N/A' }}</small>
                            </div>
                        </div>
                        </div>

                </div>
            </div>
        </div>

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
                        ['value' => 'not_taken', 'text' => 'Not Taken'],
                        ['value' => 'failed', 'text' => 'Failed'],
                        ['value' => 'dropped', 'text' => 'Dropped'],
                        ['value' => 'incomplete', 'text' => 'Incomplete'],
                        ['value' => 'completed', 'text' => 'Completed'],
                    ]"
                    placeholder="Select Status"
                />
            </div>
        </div>

        <div class="mt-0 pb-2">
            <div class="d-flex flex-wrap justify-content-center gap-4">

                <span>
                    <i class="fa-solid fa-minus-circle text-light"></i> Not Taken
                </span>

                <span>
                    <i class="fa-solid fa-times-circle text-danger"></i> Failed
                </span>

                <span>
                    <i class="fa-solid fa-minus-circle text-dark"></i> Dropped
                </span>

                <span>
                    <i class="fa-solid fa-minus-circle text-warning"></i> Incomplete
                </span>

                <span>
                    <i class="fa-solid fa-check-circle text-success"></i> Completed
                </span>

            </div>
        </div>

        <!-- Year Level Tabs -->
        <div class="mb-0 pb-0 tab-scroll-wrapper" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
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
            <th>Grade</th>
            <th>Remarks</th>
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
                        <a href="{{ route('officer.student.progress.pdf', $student_id) }}" class="btn btn-danger" id="btnDownloadPDF" target="_blank">
                            <i class="fa-solid fa-file-pdf fa-1x me-2"></i>
                            Download PDF
                        </a>
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


$(document).ready(function() {

    $('#filter-status').select2({
        minimumResultsForSearch: -1,
        placeholder: 'All Status'
    });

    let selectedYearLevel = '1';

    // Initialize DataTable
    const academicProgressTable = new GenericDataTable({
        order: [[7, "asc"], [8, "asc"], [9, "asc"], [1, "asc"]],
        tableId: 'academicProgressTable',
        ajaxUrl: "{{ route('officer.student.progress.data', $student_id) }}",
        ajaxData: function(d) {
            d.status = $('#filter-status').val();
            d.year_level = selectedYearLevel;
        },
        columns: [
            { data: "id", visible: false },
            { data: "subject.code" },
            { data: "subject.name" },
            {
                data: "lecture_status",
                responsivePriority: 1,
                render: (data, type, row) => {
                    if (!row.has_lec)
                    {
                        return '<span class="text-muted">-</span>';
                    }

                    var display;

                    if (row.lecture_status == "failed") {
                        display = '<i class="fa-solid fa-times-circle text-danger"></i>';
                    } else if (row.lecture_status == "incomplete") {
                        display = '<i class="fa-solid fa-minus-circle text-warning"></i>';
                    } else if (row.lecture_status == "completed") {
                        display = '<i class="fa-solid fa-check-circle text-success"></i>';
                    } else if (row.lecture_status == "dropped") {
                        display = '<i class="fa-solid fa-minus-circle text-dark"></i>';
                    } else {
                        display = '<i class="fa-solid fa-minus-circle text-light"></i>';
                    }

                    return display;
                }
            },
            {
                data: "laboratory_status",
                responsivePriority: 1,
                render: (data, type, row) => {
                    if (!row.has_lab) {
                        return '<span class="text-muted">-</span>';
                    }

                    var display;
                    if (row.laboratory_status == "failed") {
                        display = '<i class="fa-solid fa-times-circle text-danger"></i>';
                    } else if (row.laboratory_status == "incomplete") {

                        display = '<i class="fa-solid fa-minus-circle text-warning"></i>';
                    } else if (row.laboratory_status == "completed") {
                        display = '<i class="fa-solid fa-check-circle text-success"></i>';
                    } else if (row.laboratory_status == "dropped") {
                        display = '<i class="fa-solid fa-minus-circle text-dark"></i>';
                    } else {
                        display = '<i class="fa-solid fa-minus-circle text-light"></i>';
                    }

                    return display;
                }
            },
            {
                data: "final_grade",
                render: (data, type, row) => {
                    var display;
                    if (row.final_grade === "DRP") {
                        display = '<i class="text-dark">DRP</i>';
                    } else if (row.final_grade === "INC") {
                        display = '<i class="text-warning">INC</i>';
                    } else if (parseFloat(row.final_grade) >= 1 && parseFloat(row.final_grade) <= 3) {
                        display = `<i class="text-success">${parseFloat(row.final_grade).toFixed(2)}</i>`;
                    }
                    else if (parseFloat(row.final_grade) < 1 || parseFloat(row.final_grade) > 3) {
                        display = `<i class="text-danger">${parseFloat(row.final_grade).toFixed(2)}</i>`;
                    }
                    else {
                        display = `<i class="text-light">-</i>`;
                    }

                    return display;
                }
            },
            {
                data: "remarks",
                render: (data, type, row) => {
                    var display;
                    if (row.remarks === "dropped") {
                        display = '<span class="badge bg-label-dark">Dropped</span>';
                    } else if (row.remarks === "incomplete") {
                        display = '<span class="badge bg-label-warning">Incomplete</span>';
                    } else if (row.remarks === "failed") {
                        display = '<span class="badge bg-label-danger">Failed</span>';
                    } else if (row.remarks === "completed") {
                        display = '<span class="badge bg-label-success">Completed</span>';
                    } else {
                        display = `<span class="text-light">-</span>`;
                    }

                    return display;
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
                $.get("{{ route('officer.student.progress.stats', $student_id) }}", (data) => {
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
                }).fail((xhr) => {
                    console.error('Error fetching stats:', xhr);
                    if (xhr.status === 500) {
                        const msg = xhr.responseJSON?.message || 'Internal server error';
                        toastr.error(msg, 'Server Error');
                        return;
                    }
                    toastr.error('Error fetching statistics. Please refresh.');
                });
            }
        }
    }).init();

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
});
</script>

@endsection
