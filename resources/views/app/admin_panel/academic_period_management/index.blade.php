@extends('layout.base')

@section('title')
Academic Period Management
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
Academic Period Management
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header title="" subtitle="Manage system academic periods">
            <button class="btn btn-primary" data-bs-toggle="offcanvas" id="btn-add" data-bs-target="#add-or-update-modal">
                <i class="fa-solid fa-plus fa-1x me-2"></i>
                Add New Academic Period
            </button>
        </x-table.page-header>
        
        <!-- Statistics Cards (Optional) -->
        <div class="row mb-4">
            
            {{-- TOTAL ACADEMIC PERIODS --}}
            <x-table.stats-card 
                id="totalAcademicPeriods" 
                title="Total Academic Periods" 
                icon="fa-solid fa-calendar fa-2x" 
                bgColor="bg-primary" 
                class="col-md-4"/>

            {{-- CURRENT ACADEMIC PERIOD --}}
            <x-table.stats-card 
                id="currentAcademicPeriod" 
                title="Current Academic Period" 
                icon="fa-solid fa-calendar-check fa-2x" 
                bgColor="bg-success" 
                class="col-md-4"/>

        </div>
        
        <!-- DataTable -->
        <x-table.table id="academicPeriodsTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Name</th>
            <th>School Year</th>
            <th>Semester</th>
            <th>Status</th>
            <th>Actions</th>
        </x-table.table>

        @include('app.admin_panel.academic_period_management.form')

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
    
    // Initialize DataTable
    const academicPeriodsTable = new GenericDataTable({
        order: [[ 1, "desc" ]],
        tableId: 'academicPeriodsTable',
        ajaxUrl: "{{ route('academic_periods.data') }}",
        columns: [
            { data: "id", visible: false },
            { data: "name" },
            { data: "school_year" },
            { data: "semester" },
            { 
                data: "is_current",
                render: (data, type, row) => {
                    if (data) {
                        return `<span class="badge bg-success">Ongoing</span>`;
                    } else {
                        return `<span class="badge bg-secondary">Closed</span>`;
                    }
                }
            },
            { 
                data: null,
                orderable: false,
                render: (data, type, row) => {
                    const toggleIcon = row.is_current
                        ? '<i class="fa-solid fa-toggle-on"></i>'
                        : '<i class="fa-solid fa-toggle-off"></i>';

                    return `
                        <button class="btn btn-sm btn-outline-primary" title="Toggle academic period status" onclick="academicPeriodCRUD.toggleStatus('${row.id}', '${row.name}')">
                            ${toggleIcon}
                        </button>
                        
                        <button class="btn btn-sm btn-outline-warning" title="Edit academic period: ${row.name}" onclick="academicPeriodCRUD.edit('${row.id}')">
                            <i class="fa-solid fa-pencil"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-danger" title="Delete academic period: ${row.name}" onclick="academicPeriodCRUD.delete('${row.id}', '${row.name}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        statsCards: {
            callback: (table) => {
                $.get("{{ route('academic_periods.stats') }}", (data) => {
                    $('#totalAcademicPeriods').text(data.total);
                    $('#currentAcademicPeriod').text(data.current);
                });
            }
        }
    }).init();
    
    window.academicPeriodCRUD = new GenericCRUD({
        baseUrl: '/admin/academic_periods/',
        storeUrl: "{{ route('academic_periods.store') }}",
        editUrl: "{{ route('academic_periods.edit', ':id') }}",
        updateUrl: "{{ route('academic_periods.update', ':id') }}",
        destroyUrl: "{{ route('academic_periods.destroy', ':id') }}",
        toggleUrl: "{{ route('academic_periods.toggle', ':id') }}",

        entityName: 'Academic Period',
        dataTable: academicPeriodsTable,
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
            academicPeriodCRUD.update(id, fd);
        } else {
            academicPeriodCRUD.create(fd);
        }
    });

    academicPeriodCRUD.onEditSuccess = (data) => {
        $('#add-or-update-form input[name="id"]').val(data.id);
        $('#add-or-update-form input[name="year_start"]').val(data.year_start);
        $('#add-or-update-form input[name="year_end"]').val(data.year_end);
        $('#add-or-update-form select[name="semester"]').val(data.semester).trigger('change');
    };

});
</script>

@endsection