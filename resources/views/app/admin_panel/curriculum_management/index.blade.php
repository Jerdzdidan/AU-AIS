@extends('layout.base')

@section('title')
Curriculums Management
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
Curriculum Management
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header title="" subtitle="Manage system programs">
            <button class="btn btn-primary" data-bs-toggle="offcanvas" id="btn-add" data-bs-target="#add-or-update-modal">
                <i class="fa-solid fa-plus fa-1x me-2"></i>
                Add New Curriculum
            </button>
        </x-table.page-header>
        
        <!-- Statistics Cards (Optional) -->
        <div class="row mb-4">
            
            {{-- TOTAL CURRICULA --}}
            <x-table.stats-card 
                id="totalCurricula" 
                title="Total Curricula" 
                icon="fa-solid fa-file-pen fa-2x" 
                bgColor="bg-primary" 
                class="col-md-4"/>

            {{-- ACTIVE CURRICULA --}}
            <x-table.stats-card 
                id="activeCurricula" 
                title="Active Curricula" 
                icon="fa-solid fa-check fa-2x" 
                bgColor="bg-success" 
                class="col-md-4"/>

            {{-- INACTIVE CURRICULA --}}
            <x-table.stats-card 
                id="inactiveCurricula"
                title="Inactive Curricula"
                icon="fa-solid fa-xmark fa-2x"
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
        <x-table.table id="curriculaTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Curricula</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
        </x-table.table>

        @include('app.admin_panel.curriculum_management.form')
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
    let programsCache = [];
    prefetchAndInitSelect2('#program_id', "{{ route('programs.select') }}", 'Select a program');
    
    $('#filter-status').select2({
        minimumResultsForSearch: -1,
        placeholder: 'All Status'
    });

    // Initialize DataTable
    const curriculaTable = new GenericDataTable({
        tableId: 'curriculaTable',
        ajaxUrl: "{{ route('curricula.data') }}",
        ajaxData: function(d) {
            d.status = $('#filter-status').val();
        },
        columns: [
            { data: "id", visible: false },
            { data: "name" },
            { data: "description" },
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
                render: (data, type, row) => {
                    const toggleIcon = row.is_active
                        ? '<i class="fa-solid fa-toggle-on"></i>'
                        : '<i class="fa-solid fa-toggle-off"></i>';

                    const subjectUrl = "{{ route('subjects.index', ':id') }}".replace(':id', row.id);

                    return `
                        <a href="${subjectUrl}" class="btn btn-sm btn-outline-info" title="Manage subjects for curriculum: ${row.name}">
                            <i class="fa-solid fa-book"></i>
                        </a>

                        <button class="btn btn-sm btn-outline-primary" title="Toggle curriculum status" onclick="curriculumCRUD.toggleStatus('${row.id}', '${row.name}')">
                            ${toggleIcon}
                        </button>

                        <button class="btn btn-sm btn-outline-warning" title="Edit curriculum: ${row.name}" onclick="curriculumCRUD.edit('${row.id}')">
                            <i class="fa-solid fa-pencil"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-danger" title="Delete curriculum: ${row.name}" onclick="curriculumCRUD.delete('${row.id}', '${row.name}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        statsCards: {
            total: 'totalCurricula',
            callback: (table) => {
                $.get("{{ route('curricula.stats') }}", (data) => {
                    $('#totalCurricula').text(data.total);
                    $('#activeCurricula').text(data.active);
                    $('#inactiveCurricula').text(data.inactive);
                });
            }
        }
    }).init();
    
    window.curriculumCRUD = new GenericCRUD({
        baseUrl: '/admin/curricula/',
        storeUrl: "{{ route('curricula.store') }}",
        editUrl: "{{ route('curricula.edit', ':id') }}",
        updateUrl: "{{ route('curricula.update', ':id') }}",
        destroyUrl: "{{ route('curricula.destroy', ':id') }}",
        toggleUrl: "{{ route('curricula.toggle', ':id') }}",

        entityName: 'Curriculum',
        dataTable: curriculaTable,
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
            curriculumCRUD.update(id, fd);
        } else {
            curriculumCRUD.create(fd);
        }
    });

    curriculumCRUD.onEditSuccess = (data) => {
        $('#add-or-update-form input[name="id"]').val(data.id);
        $('#add-or-update-form textarea[name="description"]').val(data.description);

        setSelect2Value('#program_id', data.program_id);
    };

    $('#add-or-update-modal').on('hidden.bs.offcanvas', function() {
        $('#add-or-update-form')[0].reset();
        resetSelect2('#program_id');
    });

    $('#filter-status').on('change', function() {
        curriculaTable.reload();
    });

});
</script>

@endsection