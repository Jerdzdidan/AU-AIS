@extends('layout.base')

@section('title')
Departments Management
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
Departments Management
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header title="" subtitle="Manage system departments">
            <button class="btn btn-primary" data-bs-toggle="offcanvas" id="btn-add" data-bs-target="#add-or-update-modal">
                <i class="fa-solid fa-plus fa-1x me-2"></i>
                Add New Department
            </button>
        </x-table.page-header>
        
        <!-- Statistics Cards (Optional) -->
        <div class="row mb-4">
            
            {{-- TOTAL DEPARTMENTS --}}
            <x-table.stats-card 
                id="totalDepartments" 
                title="Total Departments" 
                icon="fa-solid fa-building fa-2x" 
                bgColor="bg-primary" 
                class="col-md-4"/>

        </div>
        
        <!-- DataTable -->
        <x-table.table id="departmentsTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Name</th>
            <th>Code</th>
            <th>Department Head</th>
            <th>Actions</th>
        </x-table.table>

        @include('app.admin_panel.department_management.form')

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/shared/generic-datatable.js') }}"></script>
<script src="{{ asset('js/shared/generic-crud.js') }}"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    const departmentsTable = new GenericDataTable({
        tableId: 'departmentsTable',
        ajaxUrl: "{{ route('departments.data') }}",
        columns: [
            { data: "id", visible: false },
            { data: "name" },
            { data: "code" },
            { data: "head_of_department" },
            { 
                data: null,
                orderable: false,
                render: (data, type, row) => {
                    return `
                        <button class="btn btn-sm btn-outline-warning" title="Edit department: ${row.name}" onclick="departmentCRUD.edit('${row.id}')">
                            <i class="fa-solid fa-pencil"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-danger" title="Delete department: ${row.name}" onclick="departmentCRUD.delete('${row.id}', '${row.name}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        statsCards: {
            total: 'totalDepartments',
            callback: (table) => {
                $.get("{{ route('departments.stats') }}", (data) => {
                    $('#totalDepartments').text(data.total);
                });
            }
        }
    }).init();
    
    window.departmentCRUD = new GenericCRUD({
        baseUrl: '/admin/departments/',
        storeUrl: "{{ route('departments.store') }}",
        editUrl: "{{ route('departments.edit', ':id') }}",
        updateUrl: "{{ route('departments.update', ':id') }}",
        destroyUrl: "{{ route('departments.destroy', ':id') }}",

        entityName: 'Department',
        dataTable: departmentsTable,
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
            departmentCRUD.update(id, fd);
        } else {
            departmentCRUD.create(fd);
        }
    });

    departmentCRUD.onEditSuccess = (data) => {
        $('#add-or-update-form input[name="id"]').val(data.id);
        $('#add-or-update-form input[name="name"]').val(data.name);
        $('#add-or-update-form input[name="code"]').val(data.code);
        $('#add-or-update-form input[name="head_of_department"]').val(data.head_of_department);
    };

});
</script>

@endsection