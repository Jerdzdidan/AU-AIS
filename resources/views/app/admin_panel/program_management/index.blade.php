@extends('layout.base')

@section('title')
Programs Management
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
Programs Management
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header title="" subtitle="Manage system programs">
            <button class="btn btn-primary" data-bs-toggle="offcanvas" id="btn-add" data-bs-target="#add-or-update-modal">
                <i class="fa-solid fa-plus fa-1x me-2"></i>
                Add New Program
            </button>
        </x-table.page-header>
        
        <!-- Statistics Cards (Optional) -->
        <div class="row mb-4">
            
            {{-- TOTAL PROGRAMS --}}
            <x-table.stats-card 
                id="totalPrograms" 
                title="Total Programs" 
                icon="fa-solid fa-building fa-2x" 
                bgColor="bg-primary" 
                class="col-md-4"/>

        </div>
        
        <!-- DataTable -->
        <x-table.table id="programsTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Name</th>
            <th>Code</th>
            <th>Description</th>
            <th>Department</th>
            <th>Actions</th>
        </x-table.table>

        @include('app.admin_panel.program_management.form')
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
    const programsTable = new GenericDataTable({
        tableId: 'programsTable',
        ajaxUrl: "{{ route('programs.data') }}",
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
                        <button class="btn btn-sm btn-outline-warning" title="Edit program: ${row.name}" onclick="programCRUD.edit('${row.id}')">
                            <i class="fa-solid fa-pencil"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-danger" title="Delete program: ${row.name}" onclick="programCRUD.delete('${row.id}', '${row.name}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        statsCards: {
            total: 'totalPrograms',
            callback: (table) => {
                $.get("{{ route('programs.stats') }}", (data) => {
                    $('#totalPrograms').text(data.total);
                });
            }
        }
    }).init();
    
    window.programCRUD = new GenericCRUD({
        baseUrl: '/admin/programs/',
        storeUrl: "{{ route('programs.store') }}",
        editUrl: "{{ route('programs.edit', ':id') }}",
        updateUrl: "{{ route('programs.update', ':id') }}",
        destroyUrl: "{{ route('programs.destroy', ':id') }}",

        entityName: 'Program',
        dataTable: programsTable,
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
            programCRUD.update(id, fd);
        } else {
            programCRUD.create(fd);
        }
    });

    programCRUD.onEditSuccess = (data) => {
        $('#add-or-update-form input[name="id"]').val(data.id);
        $('#add-or-update-form input[name="name"]').val(data.name);
        $('#add-or-update-form input[name="code"]').val(data.code);
        $('#add-or-update-form input[name="description"]').val(data.description);
        $('#add-or-update-form input[name="department_id"]').val(data.department_id);

        setSelect2Value('#department_id', data.department_id);
    };

    $('#add-or-update-modal').on('hidden.bs.offcanvas', function() {
        $('#add-or-update-form')[0].reset();
        resetSelect2('#department_id');
    });

});
</script>

@endsection