@extends('layout.base')

@section('title')
Admin Accounts Management
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
Admin Accounts Management
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header title="" subtitle="Manage system accounts">
            <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#add-or-update-modal">
                <i class="fa-solid fa-plus fa-1x me-2"></i>
                Add New Account
            </button>
        </x-table.page-header>
        
        <!-- Statistics Cards (Optional) -->
        <div class="row mb-4">
            
            {{-- TOTAL ADMINS --}}
            <x-table.stats-card 
                id="totalAdmins" 
                title="Total Admins" 
                icon="fa-solid fa-user fa-2x" 
                bgColor="bg-primary" 
                class="col-md-4"/>

            {{-- ACTIVE ACCOUNTS --}}
            <x-table.stats-card 
                id="activeAdmins" 
                title="Active" 
                icon="fa-solid fa-user-check fa-2x" 
                bgColor="bg-success" 
                class="col-md-4"/>

            {{-- INACTIVE ACCOUNTS --}}
            <x-table.stats-card 
                id="inactiveAdmins" 
                title="Inactive" 
                icon="fa-solid fa-user-xmark fa-2x" 
                bgColor="bg-danger" 
                class="col-md-4"/>

        </div>
        
        <!-- DataTable -->
        <x-table.table id="adminAccountsTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>User Type</th>
            <th>Status</th>
            <th>Actions</th>
        </x-table.table>

        @include('app.admin_panel.user_management.admin_accounts.form')

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/shared/generic-datatable.js') }}"></script>
<script src="{{ asset('js/shared/generic-crud.js') }}"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    const adminTable = new GenericDataTable({
        tableId: 'adminAccountsTable',
        ajaxUrl: "{{ route('users.data', $user_type) }}",
        columns: [
            { data: "id", visible: false },
            { data: "name" },
            { data: "email" },
            { data: "user_type" },
            { 
                data: "status",
                render: (data, type, row) => {
                    const status = row.status ? 'Active' : 'Inactive';
                    const badge = row.status ? 'success' : 'danger';
                    return `<span class="badge bg-${badge}">${status}</span>`;
                }
            },
            { 
                data: null,
                orderable: false,
                render: (data, type, row) => `
                    <button class="btn btn-sm btn-outline-warning" onclick="adminCRUD.edit('${row.id}')">
                        <i class="fa-solid fa-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="adminCRUD.delete('${row.id}', '${row.name}')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                `
            }
        ],
        statsCards: {
            total: 'totalAdmins',
            callback: (table) => {
                $.get("{{ route('users.stats', $user_type) }}", (data) => {
                    $('#totalAdmins').text(data.total);
                    $('#activeAdmins').text(data.active);
                    $('#inactiveAdmins').text(data.inactive);
                });
            }
        }
    }).init();
    
    // Initialize CRUD
    window.adminCRUD = new GenericCRUD({
        baseUrl: '/admin/users/',
        storeUrl: "{{ route('users.store', $user_type) }}",
        editUrl: "{{ route('users.edit', ':id') }}",
        updateUrl: "{{ route('users.update', ':id') }}",
        destroyUrl: "{{ route('users.destroy', ':id') }}",
        entityName: 'Admin',
        dataTable: adminTable,
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
            adminCRUD.update(id, fd);
        } else {
            adminCRUD.create(fd);
        }
    });

    $('input').on('input', function() {
        $(this).closest('.input-group, .form-group').find('.invalid-feedback').text('');
    });
    
    // Customized callbacks
    adminCRUD.onViewSuccess = (response) => {
        $('#viewAdminModal').modal('show');
    };

    adminCRUD.onEditSuccess = (data) => {
        $('#add-or-update-modal').offcanvas('show');

        $('#add-or-update-form input[name="name"]').val(data.name);
        $('#add-or-update-form input[name="email"]').val(data.email);
        $('#add-or-update-form input[name="user_type"]').val(data.user_type);

        $('#add-or-update-form input[name="id"]').val(data.id);

        $('#add-or-update-form button[type="submit"]').text('Update Admin');
    };

});
</script>

@endsection