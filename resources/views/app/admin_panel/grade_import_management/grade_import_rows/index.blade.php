@extends('layout.base')

@section('title')
{{ $gradeImportName }} - Grade Import Data Management
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
{{ $gradeImportName }} - Grade Import Data Management
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header
            title=""
            subtitle="Manage grade import data"
            showBackButton="true"
            backUrl="{{ route('grades.import.index') }}">

            <button class="btn btn-primary" data-bs-toggle="offcanvas" id="btn-add" data-bs-target="#add-or-update-modal">
                <i class="fa-solid fa-plus fa-1x me-2"></i>
                Add New Data
            </button>
        </x-table.page-header>

        <!-- Statistics Cards (Optional) -->
        <div class="row">

        </div>

        <div class="text-end">
            <a class="btn btn-outline-primary text-primary" data-bs-toggle="offcanvas" id="btn-import" data-bs-target="#import-modal">
                <i class="fa-solid fa-file-import fa-1x me-2"></i>
                Import CSV
            </a>
            <a class="btn btn-outline-success" href="{{ route('grades.import.download', $gradeImportId) }}" role="button">
                <i class="fa-solid fa-file-export fa-1x me-2"></i>
                Export CSV
            </a>
        </div>

        <div class="alert alert-danger mt-3" id="invalid-records-alert-alt" role="alert" style="display: none;">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            There are invalid grade import records that need to be addressed before committing. Please review and correct the errors.
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
                        ['value' => 'staged', 'text' => 'Staged'],
                        ['value' => 'committed', 'text' => 'Committed'],
                    ]"
                    placeholder="Select Status"
                />
            </div>
            <div class="col-md-2">
                <x-input.select-field
                    id="filter-validity"
                    label="Filter by Validity:"
                    icon="fa-solid fa-tags"
                    :options="[
                        ['value' => 'All', 'text' => 'All Validity'],
                        ['value' => 'valid', 'text' => 'Valid'],
                        ['value' => 'invalid', 'text' => 'Invalid'],
                    ]"
                    placeholder="Select Valdity"
                />
            </div>
            <div class="col-md-2">
                <x-input.select-field
                    id="filter-program"
                    label="Program"
                    placeholder="Select a program"
                />
        </div>

        <!-- DataTable -->
        <x-table.table id="gradeImportRowsTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Student No.</th>
            <th>Subject Code</th>
            <th>Subject Name</th>
            <th>Unit Type</th>
            <th>School Year</th>
            <th>Semester</th>
            <th>Faculty</th>
            <th>Credit Unit</th>
            <th>Program</th>
            <th>Grade</th>
            <th>Validity</th>
            <th>Status</th>
            <th>Actions</th>
        </x-table.table>

        <div class="container" id="commit-section-alt" style="display: none;">
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Commit and Display?</h6>
                            <small class="text-muted">Commit and display the staged grade data for the students to see?</small>
                        </div>
                        <div>
                            <button class="btn btn-success" id="btn-commit" onclick="commitAll()">
                                <i class="fa-solid fa-check me-2"></i>
                                Commit Records
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container" id="uncommitAll-section-alt" style="display: none;">
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Uncommit all?</h6>
                            <small class="text-muted">Uncommit all staged grade data for the students?</small>
                        </div>
                        <div>
                            <button class="btn btn-danger" id="btn-uncommit" onclick="uncommitAll()">
                                <i class="fa-solid fa-times me-2"></i>
                                Uncommit Records
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('app.admin_panel.grade_import_management.grade_import_rows.form')
        @include('app.admin_panel.grade_import_management.grade_import_rows.modal')
        @include('app.admin_panel.grade_import_management.grade_import_rows.import_form')
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/shared/generic-datatable.js') }}"></script>
<script src="{{ asset('js/shared/generic-crud.js') }}"></script>
<script src="{{ asset('js/shared/select2-init.js') }}"></script>
<script>
$(document).ready(function() {

    @if ($allCommited)
        $('#uncommitAll-section-alt').show();
    @endif

    $('#unit_type').select2({
        minimumResultsForSearch: -1,
        placeholder: 'Select Unit Type',
    });

    $('#filter-status').select2({
        minimumResultsForSearch: -1,
        placeholder: 'All Status'
    });

    $('#filter-validity').select2({
        minimumResultsForSearch: -1,
        placeholder: 'All Validity'
    });

    prefetchAndInitSelect2('#filter-program', "{{ route('programs.select') }}", 'Select a program');

    @if ($hasStagedData && $valid)
        $('#commit-section-alt').show();
    @elseif (!$valid)
        $('#invalid-records-alert-alt').show();
    @endif

    // Initialize DataTable
    window.gradeImportRowsTable = new GenericDataTable({
        order: [[2, 'desc']],
        tableId: 'gradeImportRowsTable',
        ajaxUrl: "{{ route('grades.import.rows.data', $gradeImportId) }}",
        ajaxData: function(d) {
            d.status = $('#filter-status').val();
            d.validity = $('#filter-validity').val();
            d.program = $('#filter-program').val();
        },
        columns: [
            { data: "id", visible: false },
            { data: "student_number" },
            { data: "subject_code" },
            { data: "subject_name" },
            { data: "unit_type" },
            { data: "school_year", className: "none" },
            { data: "semester", className: "none" },
            { data: "faculty", className: "none" },
            { data: "credit_unit", className: "none" },
            { data: "program_code", className: "none"},
            {
                data: "grade",
                render: (data, type, row) => {
                    const grade = data;
                    var display;

                    if (grade == -1)
                    {
                        display = "DRP";
                    }
                    else if (grade == 0)
                    {
                        display = "INC";
                    }
                    else
                    {
                        display = grade;
                    }

                    return `${display}`;
                }
            },
            {
                data: "validity",
                render: (data, type, row) => {
                    const badge = (data === 'valid') ? 'success' : 'danger';

                    return `<span class="badge bg-label-${badge}">${data}</span>`;
                }
            },
            {
                data: "status",
                render: (data, type, row) => {
                    const badge = (data === 'committed') ? 'success' : 'warning';

                    return `<span class="badge bg-label-${badge}">${data}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                responsivePriority: 1,
                render: (data, type, row) => {
                    return `
                        ${row.validity === 'invalid' ? `<button class="btn btn-sm btn-outline-primary" title="See errors" onclick="openErrorModal('${row.id}')">
                            <i class="fa-solid fa-circle-question"></i>
                        </button>` : ''}

                        ${row.status === 'committed' ? `<button class="btn btn-sm btn-outline-secondary" title="Uncommit data for: ${row.student_id}" onclick="unCommit('${row.id}')">
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>` : ''}

                        ${row.status === 'staged' ? `
                            <button class="btn btn-sm btn-outline-warning" title="Edit data for: ${row.student_id}" onclick="gradeImportRowsCRUD.edit('${row.id}')">
                                <i class="fa-solid fa-pencil"></i>
                            </button>

                            <button class="btn btn-sm btn-outline-danger" title="Delete data for: ${row.student_id}" onclick="gradeImportRowsCRUD.delete('${row.id}', 'grade record for student: ${row.student_id}')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        ` : ''}
                    `;
                }
            }
        ],
        statsCards: {
            callback: (table) => {
                // $.get("", (data) => {
                //     $('#totalSubjects').text(data.total);
                //     $('#activeSubjects').text(data.active);
                //     $('#totalUnits').text(data.total_units);
                //     $('#inactiveSubjects').text(data.inactive);
                // });
            }
        }
    }).init();

    window.gradeImportRowsCRUD = new GenericCRUD({
        baseUrl: '/admin/grades/import/rows',
        storeUrl: "{{ route('grades.import.rows.store', $gradeImportId) }}",
        editUrl: "{{ route('grades.import.rows.edit', ':id') }}",
        updateUrl: "{{ route('grades.import.rows.update', ':id') }}",
        destroyUrl: "{{ route('grades.import.rows.destroy', ':id') }}",
        // toggleUrl: "",

        entityName: 'Grade Import Data',
        dataTable: window.gradeImportRowsTable,
        csrfToken: "{{ csrf_token() }}",
        form: '#add-or-update-form',
        modal: '#add-or-update-modal'
    });

    $('#add-or-update-form').on('submit', function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        const id = $(this).find('input[name="id"]').val();

        if (id) {
            fd.append('_method', 'PUT');
            gradeImportRowsCRUD.update(id, fd);
        } else {
            gradeImportRowsCRUD.create(fd);
        }
    });

    gradeImportRowsCRUD.onEditSuccess = (data) => {
        $('#add-or-update-form input[name="id"]').val(data.id);
        $('#add-or-update-form input[name="student_number"]').val(data.student_number);
        $('#add-or-update-form input[name="subject_code"]').val(data.subject_code);
        $('#add-or-update-form input[name="subject_name"]').val(data.subject_name);
        $('#add-or-update-form input[name="grade"]').val(data.grade);
        $('#add-or-update-form input[name="faculty"]').val(data.faculty);
        $('#add-or-update-form input[name="credit_unit"]').val(data.credit_unit);

        $('#add-or-update-form select[name="unit_type"]').val(data.unit_type).trigger('change');
    };

    $('#add-or-update-modal').on('hidden.bs.offcanvas', function() {
        $('#add-or-update-form')[0].reset();
        $('#add-or-update-form select').val(null).trigger('change');
    });

    gradeImportRowsCRUD.onCreateSuccess = (data) => {
        if (data.allValid && data.allCommited) {
            $('#invalid-records-alert-alt').remove();
            $('#commit-section-alt').remove();
            $('#uncommitAll-section-alt').show();
        }
        else if (data.allValid && !data.allCommited) {
            $('#invalid-records-alert-alt').remove();
            $('#commit-section-alt').show();
        }
        else {
            $('#commit-section-alt').hide();
            $('#invalid-records-alert-alt').show();
        }
    };

    gradeImportRowsCRUD.onUpdateSuccess = (data) => {
        if (data.allValid && data.allCommited) {
            $('#invalid-records-alert-alt').hide();
            $('#commit-section-alt').hide();
            $('#uncommitAll-section-alt').show();
        }
        else if (data.allValid && !data.allCommited) {
            $('#invalid-records-alert-alt').hide();
            $('#commit-section-alt').show();
        }
        else {
            $('#commit-section-alt').hide();
            $('#invalid-records-alert-alt').show();
        }
    };

    gradeImportRowsCRUD.onDeleteSuccess = (data) => {
        if (data.allValid && data.allCommited) {
            $('#invalid-records-alert-alt').hide();
            $('#commit-section-alt').hide();
            $('#uncommitAll-section-alt').show();
        }
        else if (data.allValid && !data.allCommited) {
            $('#invalid-records-alert-alt').hide();
            $('#commit-section-alt').show();
        }
        else {
            $('#commit-section-alt').hide();
            $('#invalid-records-alert-alt').show();
        }
    };

    // $('#filter-status').on('change', function() {
    //     gradeImportRowsTable.reload();
    // });
    window.Import = new GenericCRUD({
        baseUrl: '/admin/grades/import/rows',
        storeUrl: "{{ route('grades.import.rows.import', $gradeImportId) }}",

        entityName: 'Grade Import Data',
        csrfToken: "{{ csrf_token() }}",
        form: '#grade-import-form',
        modal: '#import-modal'
    });

    $('#grade-import-form').on('submit', function(e) {
        e.preventDefault();
        const fd = new FormData(this);

        Import.create(fd);
    });

    Import.onCreateSuccess = (data) => {
        if (data.allValid && data.allCommited) {
            $('#invalid-records-alert-alt').hide();
            $('#commit-section-alt').hide();
            $('#uncommitAll-section-alt').show();
        }
        else if (data.allValid && !data.allCommited) {
            $('#invalid-records-alert-alt').hide();
            $('#commit-section-alt').show();
        }
        else {
            $('#commit-section-alt').hide();
            $('#invalid-records-alert-alt').show();
        }
    };

    $('#filter-status').on('change', function() {
        gradeImportRowsTable.reload();
    });
    $('#filter-validity').on('change', function() {
        gradeImportRowsTable.reload();
    });
    $('#filter-program').on('change', function() {
        gradeImportRowsTable.reload();
    });

});

function openErrorModal(rowId) {
    $('#modal').modal('show');

    populateErrorMessages(rowId);
}

function populateErrorMessages(rowId) {
    $.get("{{ route('grades.import.rows.errors', ':id') }}".replace(':id', rowId), function (data) {
        const container = $('#error-messages-container');
        container.empty();

        if (!data.messages) {
            container.append(
                '<div class="alert alert-info">No errors found.</div>'
            );
            return;
        }

        let messages = [];
        let parsedMessages = data.messages;

        if (typeof data.messages === 'string') {
            try {
                parsedMessages = JSON.parse(data.messages);
            } catch (e) {
                parsedMessages = data.messages;
            }
        }

        if (Array.isArray(parsedMessages)) {
            parsedMessages.forEach(item => {
                if (Array.isArray(item)) {
                    messages.push(...item);
                } else {
                    messages.push(item);
                }
            });
        } else if (typeof parsedMessages === 'object') {
            Object.values(parsedMessages).forEach(value => {
                if (Array.isArray(value)) {
                    messages.push(...value);
                } else {
                    messages.push(value);
                }
            });
        } else {
            messages.push(parsedMessages);
        }

        if (messages.length === 0) {
            container.append(
                '<div class="alert alert-info">No errors found.</div>'
            );
            return;
        }

        messages.forEach(msg => {
            container.append(
                `<div class="alert alert-danger" role="alert">${msg}</div>`
            );
        });
    });
}

function unCommit(rowId) {
    Swal.fire({
        title: 'Confirm Uncommit',
        html: `Are you sure you want to uncommit this grade record?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F8BB86",
        cancelButtonColor: "#91a8b3ff",
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('grades.import.rows.uncommit', ':id') }}".replace(':id', rowId),
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: (response) => {
                    toastr.success(response.message || "Grade record has been uncommitted.");
                    window.gradeImportRowsTable.reload();
                    $('#commit-section-alt').show();
                },
                error: (xhr) => {
                    if (xhr.status === 403) {
                        const msg = xhr.responseJSON?.message || 'Action forbidden';
                        toastr.error(msg, 'Forbidden');
                        return;
                    }

                    if (xhr.status === 500) {
                        const msg = xhr.responseJSON?.message || 'Internal server error';
                        toastr.error(msg, 'Server Error');
                        return;
                    }

                    toastr.error(xhr.responseJSON?.message || 'An error occurred while uncommitting the record.', 'Error');
                }
            });
        }
    });
}

function commitAll() {
    Swal.fire({
        title: 'Confirm Commit All',
        html: `Are you sure you want to commit all valid grade import records? This action cannot be undone.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F8BB86",
        cancelButtonColor: "#91a8b3ff",
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('grades.import.rows.commitAll', $gradeImportId) }}",
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: (response) => {
                    toastr.success(response.message || "All valid grade import records have been committed.");
                    $('#commit-section').hide();
                    $('#commit-section-alt').hide();
                    $('#invalid-records-alert').hide();
                    $('#invalid-records-alert-alt').hide();
                    $('#uncommitAll-section-alt').show();
                    window.gradeImportRowsTable.reload();
                },
                error: (xhr) => {
                    if (xhr.status === 403) {
                        const msg = xhr.responseJSON?.message || 'Action forbidden';
                        toastr.error(msg, 'Forbidden');
                        return;
                    }

                    if (xhr.status === 500) {
                        const msg = xhr.responseJSON?.message || 'Internal server error';
                        toastr.error(msg, 'Server Error');
                        return;
                    }

                    toastr.error(xhr.responseJSON?.message || 'An error occurred while committing the records.', 'Error');
                }
            });
        }
    });
}

function uncommitAll() {
    Swal.fire({
        title: 'Confirm Uncommit All',
        html: `Are you sure you want to uncommit all valid grade import records? This action cannot be undone.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F8BB86",
        cancelButtonColor: "#91a8b3ff",
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('grades.import.rows.uncommitAll', $gradeImportId) }}",
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: (response) => {
                    toastr.success(response.message || "All valid grade import records have been uncommitted.");
                    $('#commit-section-alt').show();
                    $('#invalid-records-alert-alt').hide();
                    $('#uncommitAll-section-alt').hide();
                    window.gradeImportRowsTable.reload();
                },
                error: (xhr) => {
                    if (xhr.status === 403) {
                        const msg = xhr.responseJSON?.message || 'Action forbidden';
                        toastr.error(msg, 'Forbidden');
                        return;
                    }

                    if (xhr.status === 500) {
                        const msg = xhr.responseJSON?.message || 'Internal server error';
                        toastr.error(msg, 'Server Error');
                        return;
                    }

                    toastr.error(xhr.responseJSON?.message || 'An error occurred while committing the records.', 'Error');
                }
            });
        }
    });
}


</script>

@endsection
