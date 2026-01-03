@extends('layout.base')

@section('title')
Grades
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app/student_portal/grades/main.css') }}">
@endsection

@section('nav_title')
Grades
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header 
            title="" 
            subtitle="View grade details"
        />

        {{-- Grade Cards --}}
        @foreach($gradeCards as $card)
            <x-grade.grade-card 
                :academicPeriod="$card['academicPeriod']" 
                :gwa="$card['gwa']" 
                :totalUnits="$card['totalUnits']"
            >
                <x-grade.grade-desktop-table>
                    @foreach($card['subjects'] as $subject)
                        <x-grade.grade-desktop-table-row 
                            :subjectCode="$subject['subjectCode']" 
                            :subjectName="$subject['subjectName']" 
                            :unitType="$subject['unitType']" 
                            :creditUnit="$subject['creditUnit']" 
                            :faculty="$subject['faculty']" 
                            :grade="$subject['grade']"
                        />
                    @endforeach
                </x-grade.grade-desktop-table>

                <x-grade.grade-mobile-table>
                    @foreach($card['subjects'] as $subject)
                        <x-grade.grade-mobile-table-row 
                            :subjectCode="$subject['subjectCode']" 
                            :subjectName="$subject['subjectName']" 
                            :unitType="$subject['unitType']" 
                            :creditUnit="$subject['creditUnit']" 
                            :faculty="$subject['faculty']" 
                            :grade="$subject['grade']"
                        />
                    @endforeach
                </x-grade.grade-mobile-table>
            </x-grade.grade-card>
        @endforeach
    </div>
</div>

<script>
function toggleDetails(row) {
    // Get the next row (details row)
    const detailsRow = row.nextElementSibling;
    const icon = row.querySelector('.toggle-icon');
    
    // Toggle the details row
    detailsRow.classList.toggle('show');
    
    // Rotate the icon
    icon.classList.toggle('rotated');
}
</script>
@endsection