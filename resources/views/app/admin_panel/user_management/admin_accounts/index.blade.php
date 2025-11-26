@extends('layout.base')

@section('title')
Accounts Management
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
Accounts Management
@endsection

@section('content')
@csrf
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header title="Accounts Management" subtitle="Manage system accounts">
            <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#addAdminModal">
                <i class="fa-solid fa-plus fa-1x me-2"></i>
                Add New Account
            </button>
        </x-table.page-header>
        
        <!-- Statistics Cards (Optional) -->
        <div class="row mb-4">
            {{-- TOTAL ADMINS --}}
            <x-table.statistics-card 
                id = "totalAdmins"
                title = "Total Admins"
                icon = "fa-solid fa-user fa-2x"
                bgColor = "bg-primary"
            />

            {{-- ACTIVE ACCOUNTS --}}
            <x-table.statistics-card 
                id = "activeAdmins"
                title = "Active"
                icon = "fa-solid fa-user-check fa-2x"
                bgColor = "bg-success"
            />

            {{-- INACTIVE ACCOUNTS --}}
            <x-table.statistics-card 
                id = "inactiveAdmins"
                title = "Inactive"
                icon = "fa-solid fa-user-xmark fa-2x"
                bgColor = "bg-danger"
            />
        </div>
        
        <!-- DataTable -->
        <x-table.table id="adminAccountsTable">
            {{-- Columns --}}
            <th>Admin</th>
            <th>Email</th>
            <th>Status</th>
            <th>Created at</th>
            <th>Actions</th>
        </x-table.table>

        @include('app.admin_panel.user_management.admin_accounts.form')

    </div>
</div>


{% block script %}
<script>
    window.adminUrls = {
        adminList: "{% url 'admin_panel:admin_accounts_data' %}",
        createAdmin: "{% url 'admin_panel:create_admin_account' %}",
        updateAdmin: "{% url 'admin_panel:admin_accounts' %}", 
        getAdmin: "{% url 'admin_panel:admin_accounts' %}",
        getDepartments: "{% url 'core:get_departments_data' %}"
    };
    const csrftoken = document.querySelector('[name=csrfmiddlewaretoken]').value;
</script>
<script src="{% static 'js/admin_panel/user_management/admin.js' %}"></script>
<script src="{% static 'js/admin_panel/user_management/delete_user.js' %}"></script>
{% endblock  %} 