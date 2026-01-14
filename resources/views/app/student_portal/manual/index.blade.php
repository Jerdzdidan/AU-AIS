@extends('layout.base')

@section('title')
Student Manual
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
    <style>
        .manual-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .manual-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eaeaea;
        }
        .manual-content {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .section-title {
            color: #2c3e50;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        .info-box {
            background: #e8f4fc;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 5px 5px 0;
        }
        .manual-viewer {
            height: 800px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 20px;
        }
        .viewer-note {
            text-align: center;
            color: #7f8c8d;
            font-size: 0.9em;
            margin-top: 10px;
        }
    </style>
@endsection

@section('nav_title')
Student Manual
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header 
            title="Student Manual" 
            subtitle="Official Arellano University Student Handbook"
        />
        
        <div class="manual-container">
            <div class="manual-content">
                <!-- Manual Header -->
                <div class="manual-header">
                    <h2>Arellano University Student Manual</h2>
                    <p class="text-muted">2019 Edition</p>
                </div>
                
                <!-- Manual Introduction -->
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="section-title">Welcome to Arellano University</h3>
                        <p>This Student Manual contains the official policies, rules, and regulations of Arellano University. It serves as your guide to understanding university procedures, academic requirements, and student responsibilities.</p>
                        
                        <div class="info-box">
                            <strong>Important:</strong> All enrolled students are required to be familiar with and adhere to the contents of this manual. The manual is for viewing purposes only and cannot be downloaded.
                        </div>
                        
                        <!-- Manual Viewer Section -->
                        <div class="mt-4">
                            <h4 class="section-title">View Student Manual</h4>
                            <p>The complete Student Manual is available for viewing through the link below:</p>
                            
                            <div class="text-center mt-4">
                                <a href="https://drive.google.com/file/d/1567CqxXNDy2WuQqsnOHZkBxWpV0Yebtc/view" 
                                   target="_blank" 
                                   class="btn btn-primary btn-lg">
                                    <i class="fa-solid fa-book-open me-2"></i>
                                    View Student Manual
                                </a>
                                <p class="viewer-note">
                                    Click the button above to view the Arellano University Student Manual in a new window.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Manual Sections Overview -->
                        <div class="mt-5">
                            <h3 class="section-title">Manual Contents Overview</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Academic Policies</h5>
                                    <ul>
                                        <li>Admission and Enrollment Procedures</li>
                                        <li>Academic Load and Classification</li>
                                        <li>Grading System and Scholarship</li>
                                        <li>Attendance and Examination Policies</li>
                                        <li>Academic Integrity and Honesty</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Student Regulations</h5>
                                    <ul>
                                        <li>Code of Student Conduct</li>
                                        <li>Student Rights and Responsibilities</li>
                                        <li>Disciplinary Procedures</li>
                                        <li>Grievance Mechanisms</li>
                                        <li>Student Organization Guidelines</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5>Administrative Procedures</h5>
                                    <ul>
                                        <li>Registration and Enrollment Process</li>
                                        <li>Adding/Dropping of Subjects</li>
                                        <li>Shifting and Transfer Procedures</li>
                                        <li>Leave of Absence and Readmission</li>
                                        <li>Graduation Requirements</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>University Services</h5>
                                    <ul>
                                        <li>Library Services and Resources</li>
                                        <li>Laboratory Facilities Usage</li>
                                        <li>Guidance and Counseling Services</li>
                                        <li>Health and Medical Services</li>
                                        <li>Student Development Programs</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Important Notes -->
                        <div class="info-box mt-4">
                            <h5><i class="fa-solid fa-circle-info me-2"></i>Important Notes:</h5>
                            <ul class="mb-0">
                                <li>This manual is the official reference for all university policies</li>
                                <li>Policies are subject to change with official university announcements</li>
                                <li>For clarification on any policy, consult your department head or the Office of Student Affairs</li>
                                <li>The online version is regularly updated with current policies</li>
                            </ul>
                        </div>
                        
                        <!-- Footer -->
                        <div class="text-center mt-5 pt-4 border-top">
                            <p class="text-muted">
                                <i class="fa-solid fa-university me-2"></i>
                                Arellano University Student Manual &copy; 2019
                            </p>
                            <p class="small text-muted">
                                This document is for official university use and is accessible to enrolled students only.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Simple confirmation for manual viewing
    $('a[href*="drive.google.com"]').on('click', function(e) {
        console.log('Opening Arellano University Student Manual');
        // Optional: Add analytics tracking here
    });
});
</script>
@endsection