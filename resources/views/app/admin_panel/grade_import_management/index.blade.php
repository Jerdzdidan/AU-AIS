@extends('layout.base')

@section('title')
Grades Import Management
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
Grades Import Management
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header title="" subtitle="Manage system grade imports">
            <button class="btn btn-primary" data-bs-toggle="offcanvas" id="btn-add" data-bs-target="#grade-import-create-modal">
                <i class="fa-solid fa-plus fa-1x me-2"></i>
                Import Grades
            </button>
        </x-table.page-header>

        
        <!-- DataTable -->
        <x-table.table id="gradeImportsTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Filename</th>
            <th>Academic Period</th>
            <th>Valid Rows</th>
            <th>Invalid Rows</th>
            <th>Total Rows</th>
            <th>Status</th>
            <th>Processed At</th>
            <th>Actions</th>
        </x-table.table>
                  
        @include('app.admin_panel.grade_import_management.create_form')
        @include('app.admin_panel.grade_import_management.update_form')
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/shared/generic-datatable.js') }}"></script>
<script src="{{ asset('js/shared/generic-crud.js') }}"></script>
<script src="{{ asset('js/shared/select2-init.js') }}"></script>

<script>
$(document).ready(function() {
    prefetchAndInitSelect2('#academic_period_id', "{{ route('academic_periods.select') }}", 'Select academic period');

    prefetchAndInitSelect2('#academic_period_update_id', "{{ route('academic_periods.select') }}", 'Select academic period');

    // Initialize DataTable
    const gradeImportsTable = new GenericDataTable({
        tableId: 'gradeImportsTable',
        ajaxUrl: "{{ route('grades.import.data') }}",
        columns: [
            { data: "id", visible: false },
            {
                data: "filename",
                render: (data, type, row) => {
                    const gradeImportDownloadUrl = "{{ route('grades.import.download', ':id') }}".replace(':id', row.id);

                    return `
                            <a href="${gradeImportDownloadUrl}" class="text-primary hover-underline-ltr" title="Download CSV">
                                <i class="fa-solid fa-file-excel me-1"></i>${data}
                            </a>
                        `;
                }
            },
            { data: "academic_period_name"},
            { data: "valid_rows", className: "none" },
            { data: "invalid_rows", className: "none" },
            { data: "total_rows" },
            { data: "status" },
            { data: "processed_at", className: "none" },
            { 
                data: null,
                orderable: false,
                render: (data, type, row) => {
                    const gradeImportUrl = "{{ route('grades.import.rows.index', ':id') }}".replace(':id', row.id);

                    return `
                        <a href="${gradeImportUrl}" class="btn btn-sm btn-outline-info" title="Manage subjects for curriculum: ${row.name}">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-warning" title="Edit" onclick="gradeImportCRUD.edit('${row.id}')">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="gradeImportCRUD.delete('${row.id}', '${row.filename}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    }).init();

    // CRUD Operations
    window.gradeImportCRUD = new GenericCRUD({
        baseUrl: '/admin/grades/import',
        storeUrl: "{{ route('grades.import.store') }}",
        editUrl: "{{ route('grades.import.edit', ':id') }}",
        updateUrl: "{{ route('grades.import.update', ':id') }}",
        destroyUrl: "{{ route('grades.import.destroy', ':id') }}",

        entityName: 'Grade Import',
        dataTable: gradeImportsTable,
        csrfToken: "{{ csrf_token() }}",
        form: '#grade-import-update-form',
        modal: '#grade-import-update-modal'
    });

    // Create form submission
    $('#grade-import-create-form').on('submit', function(e) {
        e.preventDefault();
        const fd = new FormData(this);

        gradeImportCRUD.form = '#grade-import-create-form';
        gradeImportCRUD.modal = '#grade-import-create-modal';

        gradeImportCRUD.$form = $(gradeImportCRUD.form);
        gradeImportCRUD.$modal = $(gradeImportCRUD.modal);

        gradeImportCRUD.create(fd);
    });

    // Update form submission
    $('#grade-import-update-form').on('submit', function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        const id = $(this).find('input[name="id"]').val();

        gradeImportCRUD.form = '#grade-import-update-form';
        gradeImportCRUD.modal = '#grade-import-update-modal';

        gradeImportCRUD.$form = $(gradeImportCRUD.form);
        gradeImportCRUD.$modal = $(gradeImportCRUD.modal);

        fd.append('_method', 'PUT');
        gradeImportCRUD.update(id, fd);
    });

    // Populate form on edit
    gradeImportCRUD.onEditSuccess = (data) => {
        $('#grade-import-update-form input[name="id"]').val(data.id);
        $('#grade-import-update-form input[name="filename"]').val(data.filename);

        setSelect2Value('#academic_period_update_id', data.academic_period_id);
    };

    // Reset form on modal close
    $('#grade-import-create-modal').on('hidden.bs.offcanvas', function() {
        $('#grade-import-create-form')[0].reset();

        gradeImportCRUD.form = '#grade-import-update-form';
        gradeImportCRUD.modal = '#grade-import-update-modal';

        gradeImportCRUD.$form = $(gradeImportCRUD.form);
        gradeImportCRUD.$modal = $(gradeImportCRUD.modal);

        resetSelect2('#academic_period_id');
    });

    // Reset form on modal close
    $('#grade-import-update-modal').on('hidden.bs.offcanvas', function() {
        $('#grade-import-update-form')[0].reset();

        gradeImportCRUD.form = '#grade-import-update-form';
        gradeImportCRUD.modal = '#grade-import-update-modal';

        gradeImportCRUD.$form = $(gradeImportCRUD.form);
        gradeImportCRUD.$modal = $(gradeImportCRUD.modal);

        resetSelect2('#academic_period_id');
    });

    
});
</script>
@endsection