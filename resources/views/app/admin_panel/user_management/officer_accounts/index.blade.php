@extends('layout.base')

@section('title')
Officer Accounts Management
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
Officer Accounts Management
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
            
            {{-- TOTAL OFFICERS --}}
            <x-table.stats-card 
                id="totalOfficers" 
                title="Total Officers" 
                icon="fa-solid fa-user fa-2x" 
                bgColor="bg-primary" 
                class="col-md-4"/>

            {{-- ACTIVE ACCOUNTS --}}
            <x-table.stats-card 
                id="activeOfficers" 
                title="Active" 
                icon="fa-solid fa-user-check fa-2x" 
                bgColor="bg-success" 
                class="col-md-4"/>

            {{-- INACTIVE ACCOUNTS --}}
            <x-table.stats-card 
                id="inactiveOfficers" 
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
        <x-table.table id="officerAccountsTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>User Type</th>
            <th>Department</th>
            <th>Status</th>
            <th>Actions</th>
        </x-table.table>

        @include('app.admin_panel.user_management.officer_accounts.form')

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
    let departmentsCache = [];
    prefetchAndInitSelect2('#department_id', "{{ route('departments.select') }}", 'Select a department');

    // Initialize DataTable
    const officersTable = new GenericDataTable({
        tableId: 'officerAccountsTable',
        ajaxUrl: "{{ route('users.data', 'OFFICER') }}",
        ajaxData: function(d) {
            d.status = $('#filter-status').val();
        },
        columns: [
            { data: "id", visible: false },
            { data: "name" },
            { data: "email" },
            { data: "user_type" },
            { data: "department_name" },
            { 
                data: "status",
                render: (data, type, row) => {
                    const status = row.status ? 'Active' : 'Inactive';
                    const badge = row.status ? 'success' : 'danger';
                    return `<span class="badge bg-label-${badge}">${status}</span>`;
                }
            },
            { 
                data: null,
                orderable: false,
                render: (data, type, row) => {
                    const toggleIcon = row.status
                        ? '<i class="fa-solid fa-toggle-on"></i>'
                        : '<i class="fa-solid fa-toggle-off"></i>';

                    return `
                        <button class="btn btn-sm btn-outline-primary" title="Toggle user status" onclick="officerCRUD.toggleStatus('${row.id}', '${row.name}')">
                            ${toggleIcon}
                        </button>

                        <button class="btn btn-sm btn-outline-warning" title="Edit user: ${row.name}" onclick="officerCRUD.edit('${row.id}')">
                            <i class="fa-solid fa-pencil"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-danger" title="Delete user: ${row.name}" onclick="officerCRUD.delete('${row.id}', '${row.name}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        statsCards: {
            total: 'totalOfficers',
            callback: (table) => {
                $.get("{{ route('users.stats', 'OFFICER') }}", (data) => {
                    $('#totalOfficers').text(data.total);
                    $('#activeOfficers').text(data.active);
                    $('#inactiveOfficers').text(data.inactive);
                });
            }
        }
    }).init();
    
    window.officerCRUD = new GenericCRUD({
        baseUrl: '/admin/users/',
        storeUrl: "{{ route('officers.store') }}",
        editUrl: "{{ route('officers.edit', ':id') }}",
        updateUrl: "{{ route('officers.update', ':id') }}",
        destroyUrl: "{{ route('officers.destroy', ':id') }}",
        toggleUrl: "{{ route('users.toggle', ':id') }}",

        entityName: 'Officer',
        dataTable: officersTable,
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
            officerCRUD.update(id, fd);
        } else {
            officerCRUD.create(fd);
        }
    });

    officerCRUD.onEditSuccess = (data) => {
        $('#add-or-update-form input[name="id"]').val(data.id);
        $('#add-or-update-form input[name="name"]').val(data.name);
        $('#add-or-update-form input[name="email"]').val(data.email);

        setSelect2Value('#department_id', data.department_id);
    };

    $('#add-or-update-modal').on('hidden.bs.offcanvas', function() {
        $('#add-or-update-form')[0].reset();
        resetSelect2('#department_id');
    });

    $('#filter-status').on('change', function() {
        officersTable.reload();
    });

});
</script>

@endsection