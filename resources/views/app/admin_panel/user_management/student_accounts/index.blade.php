@extends('layout.base')

@section('title')
Student Accounts Management
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
Student Accounts Management
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header title="" subtitle="Manage system accounts">
            <button class="btn btn-primary" data-bs-toggle="offcanvas" id="btn-add" data-bs-target="#add-or-update-modal">
                <i class="fa-solid fa-plus fa-1x me-2"></i>
                Add New Account
            </button>
        </x-table.page-header>
        
        <!-- Statistics Cards (Optional) -->
        <div class="row mb-4">
            
            {{-- TOTAL STUDENTS --}}
            <x-table.stats-card 
                id="totalStudents" 
                title="Total Students" 
                icon="fa-solid fa-user fa-2x" 
                bgColor="bg-primary" 
                class="col-md-4"/>

            {{-- ACTIVE ACCOUNTS --}}
            <x-table.stats-card 
                id="activeStudents" 
                title="Active" 
                icon="fa-solid fa-user-check fa-2x" 
                bgColor="bg-success" 
                class="col-md-4"/>

            {{-- INACTIVE ACCOUNTS --}}
            <x-table.stats-card 
                id="inactiveStudents" 
                title="Inactive" 
                icon="fa-solid fa-user-xmark fa-2x" 
                bgColor="bg-danger" 
                class="col-md-4"/>

        </div>

        <!-- Status Filter -->
        <div class="col-2">
            <x-input.select-field
                id="filter-status"
                label="Filter by Status:"
                icon="fa-solid fa-tags"
                :options="[
                    ['value' => 'All', 'text' => 'All Status'],
                    ['value' => 'Active', 'text' => 'Active'],
                    ['value' => 'Inactive', 'text' => 'Inactive'],
                ]"
                placeholder="Select Status"
            />
        </div>
        
        <!-- DataTable -->
        <x-table.table id="studentAccountsTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Student No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Year Level</th>
            <th>Program</th>
            <th>Status</th>
            <th>Actions</th>
        </x-table.table>

        @include('app.admin_panel.user_management.student_accounts.form')

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/shared/generic-datatable.js') }}"></script>
<script src="{{ asset('js/shared/generic-crud.js') }}"></script>
<script src="{{ asset('js/shared/select2-init.js') }}"></script>
<script src="{{ asset('js/admin_panel/utils.js') }}"></script>
<script>
$(document).ready(function() {
    $('#filter-status').select2({
        minimumResultsForSearch: -1,
        placeholder: 'All Status'
    });

    // Select2
    let programsCache = [];
    prefetchAndInitSelect2('#program_id', "{{ route('programs.select') }}", 'Select a program');

    // Initialize DataTable
    const studentsTable = new GenericDataTable({
        tableId: 'studentAccountsTable',
        ajaxUrl: "{{ route('students.data') }}",
        ajaxData: function(d) {
            d.status = $('#filter-status').val();
        },
        columns: [
            { data: "id", visible: false },
            { data: "student_number" },
            { data: "user.name" },
            { 
                data: "user.email",
                defaultContent: '---'
            },
            { data: "year_level" },
            { data: "program.code" },
            { 
                data: "user.status",
                render: (data, type, row) => {
                    const status = row.user.status ? 'Active' : 'Inactive';
                    const badge = row.user.status ? 'success' : 'danger';
                    return `<span class="badge bg-label-${badge}">${status}</span>`;
                }
            },
            { 
                data: null,
                orderable: false,
                render: (data, type, row) => {
                    const toggleIcon = row.user.status
                        ? '<i class="fa-solid fa-toggle-on"></i>'
                        : '<i class="fa-solid fa-toggle-off"></i>';

                    return `
                        <button class="btn btn-sm btn-outline-primary" title="Toggle user status" onclick="studentCRUD.toggleStatus('${row.user_id}', '${row.user.name}')">
                            ${toggleIcon}
                        </button>

                        <button class="btn btn-sm btn-outline-warning" title="Edit user: ${row.user.name}" onclick="studentCRUD.edit('${row.id}')">
                            <i class="fa-solid fa-pencil"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-danger" title="Delete user: ${row.user.name}" onclick="studentCRUD.delete('${row.id}', '${row.user.name}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        statsCards: {
            total: 'totalStudents',
            callback: (table) => {
                $.get("{{ route('users.stats', 'STUDENT') }}", (data) => {
                    $('#totalStudents').text(data.total);
                    $('#activeStudents').text(data.active);
                    $('#inactiveStudents').text(data.inactive);
                });
            }
        }
    }).init();
    
    window.studentCRUD = new GenericCRUD({
        baseUrl: '/admin/users/',
        storeUrl: "{{ route('students.store') }}",
        editUrl: "{{ route('students.edit', ':id') }}",
        updateUrl: "{{ route('students.update', ':id') }}",
        destroyUrl: "{{ route('students.destroy', ':id') }}",
        toggleUrl: "{{ route('users.toggle', ':id') }}",

        entityName: 'Student',
        dataTable: studentsTable,
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
            studentCRUD.update(id, fd);
        } else {
            studentCRUD.create(fd);
        }
    });

    studentCRUD.onEditSuccess = (data) => {
        $('#add-or-update-form input[name="id"]').val(data.id);
        $('#add-or-update-form input[name="student_number"]').val(data.student_number);
        $('#add-or-update-form input[name="name"]').val(data.name);
        $('#add-or-update-form input[name="email"]').val(data.email);
        $('#add-or-update-form input[name="year_level"]').val(data.year_level);
        $('#add-or-update-form input[name="program"]').val(data.program);

        setSelect2Value('#program_id', data.program_id);
    };

    $('#add-or-update-modal').on('hidden.bs.offcanvas', function() {
        $('#add-or-update-form')[0].reset();
        resetSelect2('#program_id');
    });

    $('#filter-status').on('change', function() {
        studentsTable.reload();
    });

});
</script>

@endsection