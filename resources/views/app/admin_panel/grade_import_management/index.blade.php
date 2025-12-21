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
        
        <!-- Statistics Cards (Optional) -->
        <div class="row mb-4">
            
        </div>
        
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
    // Select2
    let departmentsCache = [];
    prefetchAndInitSelect2('#department_id', "{{ route('departments.select') }}", 'Select a department');

    // Initialize DataTable
    const gradeImportsTable = new GenericDataTable({
        tableId: 'gradeImportsTable',
        ajaxUrl: "{{ route('grades.import.data') }}",
        columns: [
            { data: "id", visible: false },
            { data: "name" },
            { data: "code" },
            { data: "description" },
            { data: "department.name" },
            { 
                data: null,
                orderable: false,
                render: (data, type, row) => {
                    return `
                        <button class="btn btn-sm btn-outline-warning" title="Edit grade import: ${row.name}" onclick="gradeImportCRUD.edit('${row.id}')">
                            <i class="fa-solid fa-pencil"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-danger" title="Delete grade import: ${row.name}" onclick="gradeImportCRUD.delete('${row.id}', '${row.name}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        statsCards: {
            total: 'totalGradeImports',
            callback: (table) => {
                $.get("{{ route('grades.import.stats') }}", (data) => {
                    $('#totalGradeImports').text(data.total);
                });
            }
        }
    }).init();
    
    window.gradeImportCRUD = new GenericCRUD({
        baseUrl: '/admin/grades/import',
        storeUrl: "{{ route('grades.import.store') }}",
        destroyUrl: "{{ route('grades.import.destroy', ':id') }}",

        entityName: 'Grade Import',
        dataTable: gradeImportsTable,
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
            gradeImportCRUD.update(id, fd);
        } else {
            gradeImportCRUD.create(fd);
        }
    });

    gradeImportCRUD.onEditSuccess = (data) => {
        // $('#add-or-update-form input[name="id"]').val(data.id);
        // $('#add-or-update-form input[name="name"]').val(data.name);
        // $('#add-or-update-form input[name="code"]').val(data.code);
        // $('#add-or-update-form input[name="description"]').val(data.description);
        // $('#add-or-update-form input[name="department_id"]').val(data.department_id);

        // setSelect2Value('#department_id', data.department_id);
    };

    $('#add-or-update-modal').on('hidden.bs.offcanvas', function() {
        $('#add-or-update-form')[0].reset();
        resetSelect2('#department_id');
    });

});
</script>

@endsection