@extends('layout.base')

@section('title')
Student Academic Progress
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
@endsection

@section('nav_title')
Student Academic Progress
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header title="" subtitle="View students academic progress"/>

        <!-- Statistics Cards (Optional) -->
        <div class="row mb-4">

            {{-- TOTAL STUDENTS --}}
            <x-table.stats-card
                id="totalStudents"
                title="Total Students"
                icon="fa-solid fa-user fa-2x"
                bgColor="bg-primary"
                class="col-md-4"/>

        </div>

        <!-- Status Filter -->
        {{-- <div class="row"> --}}
        {{--     <div class="col-md-2"> --}}
        {{--         <x-input.select-field --}}
        {{--             id="filter-status" --}}
        {{--             label="Filter by Status:" --}}
        {{--             icon="fa-solid fa-tags" --}}
        {{--             :options="[ --}}
        {{--                 ['value' => 'All', 'text' => 'All Status'], --}}
        {{--                 ['value' => 'Active', 'text' => 'Active'], --}}
        {{--                 ['value' => 'Inactive', 'text' => 'Inactive'], --}}
        {{--             ]" --}}
        {{--             placeholder="Select Status" --}}
        {{--         /> --}}
        {{--     </div> --}}
        {{-- </div> --}}

        <!-- DataTable -->
        <x-table.table id="studentAccountsTable">
            {{-- Columns --}}
            <th>Id</th>
            <th>Student No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Year Level</th>
            <th>Program</th>
            <th>Curriculum</th>
            <th>Status</th>
            <th>Actions</th>
        </x-table.table>

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/shared/generic-datatable.js') }}"></script>
<script src="{{ asset('js/shared/select2-init.js') }}"></script>
<script src="{{ asset('js/admin_panel/utils.js') }}"></script>
<script>
$(document).ready(function() {
    // $('#filter-status').select2({
    //     minimumResultsForSearch: -1,
    //     placeholder: 'All Status'
    // });

    // Initialize DataTable
    const studentsTable = new GenericDataTable({
        tableId: 'studentAccountsTable',
        ajaxUrl: "{{ route('officer.students.data') }}",
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
            { data: "curriculum" },
            {
                data: "user.status",
                render: (data, type, row) => {
                    const status = row.user.status ? 'Active' : 'Inactive';
                    const badge = row.user.status ? 'success' : 'danger';
                    return `<span class="badge bg-label-${badge}">${status}</span>`;
                }
            },
            {
                data: "id",
                orderable: false,
                render: (data, type, row) => {
                    let url = "{{ route('officer.student.show', ':id') }}";

                    url = url.replace(':id', data);
                    return `
                        <a href="${url}" class="btn btn-sm btn-outline-info" title="View student progress">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </a>
                    `;
                }
            }
        ],
        statsCards: {
            callback: (table) => {
                $.get("{{ route('officer.students.stats') }}", (data) => {
                    $('#totalStudents').text(data.total);
                });
            }
        }
    }).init();

    $('#filter-status').on('change', function() {
        studentsTable.reload();
    });

});
</script>

@endsection
