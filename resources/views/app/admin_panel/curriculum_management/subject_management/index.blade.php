@extends('layout.base')

@section('title')
{{ $curriculum_name }} - Subject Management
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
{{ $curriculum_name }} - Subject Management
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header 
            title="" 
            subtitle="Manage curriculum subjects"
            showBackButton="true"
            backUrl="{{ route('curricula.index') }}">
            <button class="btn btn-primary" data-bs-toggle="offcanvas" id="btn-add" data-bs-target="#add-or-update-modal">
                <i class="fa-solid fa-plus fa-1x me-2"></i>
                Add New Subject
            </button>
        </x-table.page-header>
        
        <!-- Statistics Cards (Optional) -->
        <div class="row mb-4">
            
            {{-- TOTAL Subjects --}}
            <x-table.stats-card 
                id="totalSubjects" 
                title="Total Subjects" 
                icon="fa-solid fa-file-pen fa-2x" 
                bgColor="bg-primary" 
                class="col-md-3"/>
            
            {{-- TOTAL Units --}}
            <x-table.stats-card 
                id="totalUnits" 
                title="Total Units" 
                icon="fa-solid fa-calculator fa-2x" 
                bgColor="bg-info" 
                class="col-md-3"/>

            {{-- ACTIVE Subjects --}}
            <x-table.stats-card 
                id="activeSubjects" 
                title="Active Subjects" 
                icon="fa-solid fa-check fa-2x" 
                bgColor="bg-success" 
                class="col-md-3"/>

            {{-- INACTIVE Subjects --}}
            <x-table.stats-card 
                id="inactiveSubjects"
                title="Inactive Subjects"
                icon="fa-solid fa-xmark fa-2x"
                bgColor="bg-danger"
                class="col-md-3"/>

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
        <x-table.table id="subjectsTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Code</th>
            <th>Subject Name</th>
            <th>Year Level</th>
            <th>Semester</th>
            <th>Category</th>
            <th>Lec Units</th>
            <th>Lab Units</th>
            <th>Prerequisites</th>
            <th>Status</th>
            <th>Actions</th>
        </x-table.table>

        @include('app.admin_panel.curriculum_management.subject_management.form')
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/shared/generic-datatable.js') }}"></script>
<script src="{{ asset('js/shared/generic-crud.js') }}"></script>
<script>
$(document).ready(function() {
    $('#semester').select2({
        placeholder: 'Select Semester',
    });
    
    $('#subject_category').select2({
        placeholder: 'Select Category',
    });

    $('#filter-status').select2({
        minimumResultsForSearch: -1,
        placeholder: 'All Status'
    });

    // Initialize DataTable
    const subjectsTable = new GenericDataTable({
        order: [[5, "asc"], [3, "asc"], [4, "asc"]],
        tableId: 'subjectsTable',
        ajaxUrl: "{{ route('subjects.data', $curriculum_id) }}",
        ajaxData: function(d) {
            d.status = $('#filter-status').val();
        },
        columns: [
            { data: "id", visible: false },
            { data: "code" },
            { data: "name" },
            { 
                data: "year_level",
                defaultContent: '-'
            },
            { 
                data: "semester",
                defaultContent: '-'
            },
            { data: "subject_category" },
            { data: "lec_units", className: "none" },
            { data: "lab_units", className: "none" },
            { data: "prerequisites", className: "none" },
            { 
                data: "is_active",
                render: (data, type, row) => {
                    const status = row.is_active ? 'Active' : 'Inactive';
                    const badge = row.is_active ? 'success' : 'danger';
                    return `<span class="badge bg-label-${badge}">${status}</span>`;
                }
            },
            { 
                data: null,
                orderable: false,
                responsivePriority: 1,
                render: (data, type, row) => {
                    const toggleIcon = row.is_active
                        ? '<i class="fa-solid fa-toggle-on"></i>'
                        : '<i class="fa-solid fa-toggle-off"></i>';

                    return `
                        <button class="btn btn-sm btn-outline-primary" title="Toggle subject status" onclick="subjectCRUD.toggleStatus('${row.id}', '${row.name}')">
                            ${toggleIcon}
                        </button>

                        <button class="btn btn-sm btn-outline-warning" title="Edit subject: ${row.name}" onclick="subjectCRUD.edit('${row.id}')">
                            <i class="fa-solid fa-pencil"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-danger" title="Delete subject: ${row.name}" onclick="subjectCRUD.delete('${row.id}', '${row.name}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        statsCards: {
            total: 'totalSubjects',
            callback: (table) => {
                $.get("{{ route('subjects.stats', $curriculum_id) }}", (data) => {
                    $('#totalSubjects').text(data.total);
                    $('#activeSubjects').text(data.active);
                    $('#totalUnits').text(data.total_units);
                    $('#inactiveSubjects').text(data.inactive);
                });
            }
        }
    }).init();
    
    window.subjectCRUD = new GenericCRUD({
        baseUrl: '/admin/subjects/',
        storeUrl: "{{ route('subjects.store', $curriculum_id) }}",
        editUrl: "{{ route('subjects.edit', ':id') }}",
        updateUrl: "{{ route('subjects.update', ':id') }}",
        destroyUrl: "{{ route('subjects.destroy', ':id') }}",
        toggleUrl: "{{ route('subjects.toggle', ':id') }}",

        entityName: 'Subject',
        dataTable: subjectsTable,
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
            subjectCRUD.update(id, fd);
        } else {
            subjectCRUD.create(fd);
        }
    });

    subjectCRUD.onEditSuccess = (data) => {
        $('#add-or-update-form input[name="id"]').val(data.id);
        $('#add-or-update-form input[name="code"]').val(data.code);
        $('#add-or-update-form input[name="name"]').val(data.name);
        $('#add-or-update-form input[name="year_level"]').val(data.year_level);
        $('#add-or-update-form input[name="lec_units"]').val(data.lec_units);
        $('#add-or-update-form input[name="lab_units"]').val(data.lab_units);
        $('#add-or-update-form input[name="prerequisites"]').val(data.prerequisites);

        $('#add-or-update-form select[name="semester"]').val(data.semester).trigger('change');
        $('#add-or-update-form select[name="subject_category"]').val(data.subject_category).trigger('change');
    };

    $('#add-or-update-modal').on('hidden.bs.offcanvas', function() {
        $('#add-or-update-form')[0].reset();
        $('#add-or-update-form select').val(null).trigger('change');
    });

    $('#filter-status').on('change', function() {
        subjectsTable.reload();
    });

});
</script>

@endsection