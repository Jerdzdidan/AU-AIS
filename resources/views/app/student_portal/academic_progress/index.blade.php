@extends('layout.base')

@section('title')
Academic Progress
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
Academic Progress
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header 
            title="" 
            subtitle="View academic progress details"
        />
        
        <!-- Statistics Cards (Optional) -->
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
        <ul class="nav nav-tabs border-bottom mt-2" id="checklistYearTabs" role="tablist">
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
        

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/shared/generic-datatable.js') }}"></script>
<script>
$(document).ready(function() {

    $('#filter-status').select2({
        minimumResultsForSearch: -1,
        placeholder: 'All Status'
    });

    let selectedYearLevel = '1';

    // Initialize DataTable
    const academicProgressTable = new GenericDataTable({
        order: [[6, "asc"], [7, "asc"], [8, "asc"]],
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
        
        // Update selected year level based on tab
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
        
        // Reload the table with the new year level filter
        academicProgressTable.reload();
    });

});
</script>

@endsection