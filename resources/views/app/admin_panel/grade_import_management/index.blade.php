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
            <button class="btn btn-primary" data-bs-toggle="offcanvas" id="btn-add" data-bs-target="#add-or-update-modal">
                <i class="fa-solid fa-plus fa-1x me-2"></i>
                Import Grades
            </button>
        </x-table.page-header>

        
        <!-- DataTable -->
        <x-table.table id="gradeImportsTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Filename</th>
            <th>Valid Rows</th>
            <th>Invalid Rows</th>
            <th>Total Rows</th>
            <th>Status</th>
            <th>Processed At</th>
            <th>Actions</th>
        </x-table.table>
                  

        @include('app.admin_panel.grade_import_management.form')
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/shared/generic-datatable.js') }}"></script>
<script src="{{ asset('js/shared/generic-crud.js') }}"></script>
<script src="{{ asset('js/shared/select2-init.js') }}"></script>

<script>
$(document).ready(function() {

    // Initialize DataTable
    const gradeImportsTable = new GenericDataTable({
        tableId: 'gradeImportsTable',
        ajaxUrl: "{{ route('grades.import.data') }}",
        columns: [
            { data: "id", visible: false },
            {
                data: "filename",
                render: (data, type, row) => {
                    let url = "{{ route('grades.import.download', ':id') }}"
                    return `
                            <a href="${url.replace(':id', row.id)}" class="text-primary hover-underline-ltr" title="Download CSV">
                                <i class="fa-solid fa-file-excel me-1"></i>${data}
                            </a>
                        `;
                }
            },
            { data: "valid_rows" },
            { data: "invalid_rows" },
            { data: "total_rows" },
            { data: "status" },
            { data: "processed_at" },
            { 
                data: null,
                orderable: false,
                render: (data, type, row) => {
                    return `
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
        destroyUrl: "{{ route('grades.import.destroy', ':id') }}",

        entityName: 'Grade Import',
        dataTable: gradeImportsTable,
        csrfToken: "{{ csrf_token() }}",
        form: '#grade-import-form',
        modal: '#add-or-update-modal'
    });

    $('#grade-import-form').on('submit', function(e) {
        e.preventDefault();
        const fd = new FormData(this);

        gradeImportCRUD.create(fd);
    });

    // Reset form when modal closes
    $('#add-or-update-modal').on('hidden.bs.offcanvas', function() {
        $('#grade-import-form')[0].reset();
    });

    
});
</script>
@endsection