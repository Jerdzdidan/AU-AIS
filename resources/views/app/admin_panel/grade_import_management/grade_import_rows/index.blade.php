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
            <a class="btn btn-outline-success" href="{{ route('grades.import.download', $gradeImportId) }}" role="button">
                <i class="fa-solid fa-file-export fa-1x me-2"></i>
                Export CSV
            </a>
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
            <th>Grade</th>
            <th>Validity</th>
            <th>Status</th>
            <th>Actions</th>
        </x-table.table>

        <div class="container">
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Commit and Display?</h6>
                            <small class="text-muted">Commit and display the staged grade data for the students to see?</small>
                        </div>
                        <div>
                            <button class="btn btn-success" id="btn-commit">
                                <i class="fa-solid fa-check me-2"></i>
                                Commit Records
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('app.admin_panel.grade_import_management.grade_import_rows.form')
        @include('app.admin_panel.grade_import_management.grade_import_rows.modal')
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/shared/generic-datatable.js') }}"></script>
<script src="{{ asset('js/shared/generic-crud.js') }}"></script>
<script>
$(document).ready(function() {
    $('#unit_type').select2({
        placeholder: 'Select Unit Type',
    });

    // Initialize DataTable
    const gradeImportRowsTable = new GenericDataTable({
        order: [[2, 'desc']],
        tableId: 'gradeImportRowsTable',
        ajaxUrl: "{{ route('grades.import.rows.data', $gradeImportId) }}",
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
            { data: "grade" },
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
                        
                        <button class="btn btn-sm btn-outline-warning" title="Edit data for: ${row.student_id}" onclick="gradeImportRowsCRUD.edit('${row.id}')">
                            <i class="fa-solid fa-pencil"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-danger" title="Delete data for: ${row.student_id}" onclick="gradeImportRowsCRUD.delete('${row.id}', 'grade record for student: ${row.student_id}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
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
        dataTable: gradeImportRowsTable,
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

    // $('#filter-status').on('change', function() {
    //     gradeImportRowsTable.reload();
    // });

    $('#btn-commit').on('click', function() {
        if (confirm('Are you sure you want to commit all valid grade import records? This action cannot be undone.')) {
            $.post("{{ route('grades.import.rows.commitAll', $gradeImportId) }}", {_token: "{{ csrf_token() }}"}, function(response) {
                alert(response.message);
                gradeImportRowsTable.reload();
            }).fail(function(xhr) {
                alert('An error occurred while committing the records: ' + xhr.responseText);
            });
        }
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

        // Parse if it's a JSON string
        if (typeof data.messages === 'string') {
            try {
                parsedMessages = JSON.parse(data.messages);
            } catch (e) {
                parsedMessages = data.messages;
            }
        }

        // Normalize everything into a flat array
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


</script>

@endsection