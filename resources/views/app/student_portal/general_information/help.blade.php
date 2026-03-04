@extends('layout.base')

@section('title')
Help
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
    <style>
        .help-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .help-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eaeaea;
        }
        .help-header h2 {
            font-weight: 700;
            color: #2c3e50;
        }
        .help-content {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }

        /* Quick Help Cards */
        .help-card {
            border: 1px solid #eaeaea;
            border-radius: 12px;
            padding: 24px 20px;
            text-align: center;
            transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
            height: 100%;
            cursor: pointer;
            text-decoration: none;
            display: block;
            color: inherit;
        }
        .help-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(105, 108, 255, 0.15);
            border-color: #696cff;
            color: inherit;
            text-decoration: none;
        }
        .help-card-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px auto;
            font-size: 1.4rem;
            color: #fff;
        }
        .help-card-icon.bg-primary { background: linear-gradient(135deg, #696cff, #8b8eff); }
        .help-card-icon.bg-success { background: linear-gradient(135deg, #71dd37, #8ee660); }
        .help-card-icon.bg-warning { background: linear-gradient(135deg, #ffab00, #ffc233); }
        .help-card-icon.bg-info    { background: linear-gradient(135deg, #03c3ec, #42d8f7); }
        .help-card h6 {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 6px;
        }
        .help-card p {
            color: #888;
            font-size: 0.82rem;
            margin-bottom: 0;
        }

        /* Section Titles */
        .section-title {
            color: #2c3e50;
            margin-top: 35px;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #696cff;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title i {
            color: #696cff;
            font-size: 1.2rem;
        }

        /* Step-by-step Guides */
        .guide-accordion .accordion-item {
            border: 1px solid #eaeaea;
            border-radius: 8px !important;
            margin-bottom: 10px;
            overflow: hidden;
            transition: box-shadow 0.3s;
        }
        .guide-accordion .accordion-item:hover {
            box-shadow: 0 2px 8px rgba(105, 108, 255, 0.1);
        }
        .guide-accordion .accordion-button {
            font-weight: 600;
            font-size: 0.92rem;
            color: #2c3e50;
            padding: 14px 20px;
            background-color: #fafafa;
            border: none;
        }
        .guide-accordion .accordion-button:not(.collapsed) {
            background-color: #f0f0ff;
            color: #696cff;
            box-shadow: none;
        }
        .guide-accordion .accordion-button:focus {
            box-shadow: none;
        }
        .guide-accordion .accordion-body {
            font-size: 0.9rem;
            color: #555;
            line-height: 1.7;
            padding: 16px 20px;
        }

        /* Steps */
        .step-list {
            list-style: none;
            padding-left: 0;
            counter-reset: step-counter;
        }
        .step-list li {
            counter-increment: step-counter;
            position: relative;
            padding-left: 40px;
            margin-bottom: 14px;
        }
        .step-list li::before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #696cff, #8b8eff);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Contact Cards */
        .contact-card {
            border: 1px solid #eaeaea;
            border-radius: 10px;
            padding: 24px;
            height: 100%;
            transition: box-shadow 0.3s;
        }
        .contact-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .contact-card h5 {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .contact-card h5 i {
            color: #696cff;
        }
        .contact-detail {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 0.9rem;
            color: #555;
        }
        .contact-detail i {
            color: #696cff;
            margin-top: 3px;
            min-width: 16px;
        }

        /* Info Box */
        .info-box {
            background: #e8f4fc;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 5px 5px 0;
        }
        .info-box-warning {
            background: #fff8e1;
            border-left: 4px solid #ffab00;
        }
    </style>
@endsection

@section('nav_title')
Help
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <!-- Page Header -->
        <x-table.page-header 
            title="" 
            subtitle="Help & Support Center"
        />

        <div class="help-container">
            <div class="help-content">
                <!-- Help Header -->
                <div class="help-header">
                    <h2><i class="fa-solid fa-life-ring me-2"></i>Help & Support</h2>
                    <p class="text-muted">Step-by-step guides, helpful tips, and contact information to help you navigate the AU Academic Information System.</p>
                </div>

                <!-- ===================== QUICK HELP CARDS ===================== -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-sm-6">
                        <a href="#guideGettingStarted" class="help-card" data-guide-target="guide-start">
                            <div class="help-card-icon bg-primary">
                                <i class="fa-solid fa-rocket"></i>
                            </div>
                            <h6>Getting Started</h6>
                            <p>Login and navigate the portal</p>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="#guideGrades" class="help-card" data-guide-target="guide-grades">
                            <div class="help-card-icon bg-success">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <h6>Viewing Grades</h6>
                            <p>Check your grade records</p>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="#guideProgress" class="help-card" data-guide-target="guide-progress">
                            <div class="help-card-icon bg-warning">
                                <i class="fa-solid fa-user-graduate"></i>
                            </div>
                            <h6>Academic Progress</h6>
                            <p>Track your curriculum completion</p>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="#guideAccount" class="help-card" data-guide-target="guide-account">
                            <div class="help-card-icon bg-info">
                                <i class="fa-solid fa-user-gear"></i>
                            </div>
                            <h6>Account Settings</h6>
                            <p>Update your account details</p>
                        </a>
                    </div>
                </div>

                <!-- ===================== STEP-BY-STEP GUIDES ===================== -->
                <h4 class="section-title">
                    <i class="fa-solid fa-book-open"></i> Step-by-Step Guides
                </h4>

                <div class="accordion guide-accordion" id="accordionGuides">
                    <!-- Getting Started -->
                    <div class="accordion-item" id="guideGettingStarted">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guide-start">
                                <i class="fa-solid fa-rocket me-2 text-primary"></i>
                                Getting Started — Logging In & Navigating the Portal
                            </button>
                        </h2>
                        <div id="guide-start" class="accordion-collapse collapse" data-bs-parent="#accordionGuides">
                            <div class="accordion-body">
                                <ol class="step-list">
                                    <li>Open your browser and go to the <strong>AU-AIS portal URL</strong> provided by your university.</li>
                                    <li>On the authentication page, click the <strong>"Student Login"</strong> option.</li>
                                    <li>Enter your <strong>Student ID number</strong> and <strong>password</strong>. If it's your first time, your default password is typically your Student ID number.</li>
                                    <li>After logging in, you'll see the <strong>Home page</strong> with the latest university announcements.</li>
                                    <li>Use the <strong>sidebar menu</strong> on the left to navigate between pages — Academic Progress, Grades, Manual, FAQs, and Help.</li>
                                    <li>If prompted to provide an email address, enter a valid email and click <strong>"Save Email"</strong> to continue.</li>
                                </ol>
                                <div class="info-box mt-3">
                                    <strong><i class="fa-solid fa-lightbulb me-1"></i> Tip:</strong> Bookmark the portal URL in your browser for easy access next time!
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Viewing Grades -->
                    <div class="accordion-item" id="guideGrades">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guide-grades">
                                <i class="fa-solid fa-star me-2 text-success"></i>
                                How to View Your Grades
                            </button>
                        </h2>
                        <div id="guide-grades" class="accordion-collapse collapse" data-bs-parent="#accordionGuides">
                            <div class="accordion-body">
                                <ol class="step-list">
                                    <li>Log in to the student portal using your credentials.</li>
                                    <li>In the sidebar, under <strong>"Academic Information"</strong>, click <strong>"Grades"</strong>.</li>
                                    <li>Your grades will be displayed in cards organized by <strong>academic period</strong> (e.g., "1st Semester 2024-2025").</li>
                                    <li>Each card shows the subject code, subject name, unit type, credit units, faculty name, and your <strong>final grade</strong>.</li>
                                    <li>Your <strong>GWA (General Weighted Average)</strong> and total units are displayed at the top of each period card.</li>
                                </ol>
                                <div class="info-box mt-3">
                                    <strong><i class="fa-solid fa-lightbulb me-1"></i> Tip:</strong> On mobile devices, the table switches to a compact card layout for easier reading. Tap on a subject row to expand its details.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Progress -->
                    <div class="accordion-item" id="guideProgress">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guide-progress">
                                <i class="fa-solid fa-user-graduate me-2 text-warning"></i>
                                How to Check Your Academic Progress
                            </button>
                        </h2>
                        <div id="guide-progress" class="accordion-collapse collapse" data-bs-parent="#accordionGuides">
                            <div class="accordion-body">
                                <ol class="step-list">
                                    <li>Log in to the student portal using your credentials.</li>
                                    <li>In the sidebar, under <strong>"Academic Information"</strong>, click <strong>"Academic Progress"</strong>.</li>
                                    <li>You'll see a summary at the top showing your <strong>overall progress</strong> — total subjects, completed subjects, in-progress, and remaining.</li>
                                    <li>Below the summary is a <strong>curriculum checklist</strong> table listing all subjects in your program, organized by year level and semester.</li>
                                    <li>
                                        Subjects are color-coded by status:
                                        <ul>
                                            <li><span style="color: #28a745; font-weight: 600;">● Green</span> — Passed / Completed</li>
                                            <li><span style="color: #dc3545; font-weight: 600;">● Red</span> — Failed</li>
                                            <li><span style="color: #ffc107; font-weight: 600;">● Yellow</span> — In Progress</li>
                                            <li><span style="color: #adb5bd; font-weight: 600;">● Gray</span> — Not Yet Taken</li>
                                        </ul>
                                    </li>
                                    <li>To download a PDF copy of your progress report, click the <strong>"Download PDF"</strong> button at the top of the page.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Account Settings -->
                    <div class="accordion-item" id="guideAccount">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guide-account">
                                <i class="fa-solid fa-user-gear me-2 text-info"></i>
                                Managing Your Account
                            </button>
                        </h2>
                        <div id="guide-account" class="accordion-collapse collapse" data-bs-parent="#accordionGuides">
                            <div class="accordion-body">
                                <ol class="step-list">
                                    <li>Your account is created automatically by the university when you enroll. Your <strong>Student ID</strong> serves as your username.</li>
                                    <li>Upon your first login, the system will ask you to provide an <strong>email address</strong>. This is required to receive announcements and notifications from the university.</li>
                                    <li>To change your email or password later, please contact the <strong>Office of the Registrar</strong> or <strong>IT Help Desk</strong> at your branch campus.</li>
                                    <li>To <strong>log out</strong>, click the user icon in the top-right corner of the navigation bar and select <strong>"Log Out"</strong>.</li>
                                </ol>
                                <div class="info-box info-box-warning mt-3">
                                    <strong><i class="fa-solid fa-triangle-exclamation me-1"></i> Important:</strong> Never share your login credentials with anyone. Always log out when using shared or public computers.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Viewing the Student Manual -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guide-manual">
                                <i class="fa-solid fa-book me-2" style="color: #8e44ad;"></i>
                                How to Access the Student Manual
                            </button>
                        </h2>
                        <div id="guide-manual" class="accordion-collapse collapse" data-bs-parent="#accordionGuides">
                            <div class="accordion-body">
                                <ol class="step-list">
                                    <li>Log in to the student portal using your credentials.</li>
                                    <li>In the sidebar, under <strong>"Student Information"</strong>, click <strong>"Manual"</strong>.</li>
                                    <li>You will see an overview of the Student Manual contents.</li>
                                    <li>Click the <strong>"View Student Manual"</strong> button to open the full official manual in a new browser tab.</li>
                                </ol>
                                <div class="info-box mt-3">
                                    <strong><i class="fa-solid fa-lightbulb me-1"></i> Note:</strong> The Student Manual is for viewing purposes only and cannot be downloaded. All students are required to be familiar with its contents.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================== CONTACT & SUPPORT ===================== -->
                <h4 class="section-title mt-5">
                    <i class="fa-solid fa-headset"></i> Contact & Support
                </h4>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="contact-card">
                            <h5><i class="fa-solid fa-building-columns"></i> Office of the Registrar</h5>
                            <div class="contact-detail">
                                <i class="fa-solid fa-info-circle"></i>
                                <span>For enrollment concerns, grade inquiries, official records, and account-related requests.</span>
                            </div>
                            <div class="contact-detail">
                                <i class="fa-solid fa-clock"></i>
                                <span>Monday – Friday, 8:00 AM – 5:00 PM</span>
                            </div>
                            <div class="contact-detail">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>Visit the Registrar's Office at your respective branch campus.</span>
                            </div>
                            <div class="contact-detail">
                                <i class="fa-solid fa-envelope"></i>
                                <span>Email your branch Registrar for remote inquiries.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-card">
                            <h5><i class="fa-solid fa-desktop"></i> IT Help Desk</h5>
                            <div class="contact-detail">
                                <i class="fa-solid fa-info-circle"></i>
                                <span>For technical issues with the portal — login problems, page errors, and display issues.</span>
                            </div>
                            <div class="contact-detail">
                                <i class="fa-solid fa-clock"></i>
                                <span>Monday – Friday, 8:00 AM – 5:00 PM</span>
                            </div>
                            <div class="contact-detail">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>Visit the IT/MIS Office at your respective branch campus.</span>
                            </div>
                            <div class="contact-detail">
                                <i class="fa-solid fa-wrench"></i>
                                <span>Please include a screenshot of the issue when reporting errors.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================== TROUBLESHOOTING TIPS ===================== -->
                <h4 class="section-title mt-5">
                    <i class="fa-solid fa-screwdriver-wrench"></i> Quick Troubleshooting Tips
                </h4>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 35%;">Problem</th>
                                <th>Solution</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Page is not loading</strong></td>
                                <td>Clear your browser cache, try a different browser, or check your internet connection.</td>
                            </tr>
                            <tr>
                                <td><strong>Cannot log in</strong></td>
                                <td>Double-check your Student ID and password. If you still can't log in, contact the IT Help Desk for a password reset.</td>
                            </tr>
                            <tr>
                                <td><strong>Grades not showing</strong></td>
                                <td>Grades are released after each academic period. If grades are still missing after the release date, contact the Office of the Registrar.</td>
                            </tr>
                            <tr>
                                <td><strong>Academic progress looks incomplete</strong></td>
                                <td>Your progress is updated when grades are committed. Allow time for processing and contact the Registrar if issues persist.</td>
                            </tr>
                            <tr>
                                <td><strong>Email prompt keeps appearing</strong></td>
                                <td>Make sure to enter a valid email address and click "Save Email." If the error persists, try using a different email format.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Info Box -->
                <div class="info-box mt-4">
                    <h5><i class="fa-solid fa-circle-info me-2"></i>Need more answers?</h5>
                    <p class="mb-0">Check out the <a href="{{ route('student.faqs.index') }}"><strong>FAQs</strong></a> page for answers to the most commonly asked questions, or refer to the <a href="{{ route('student.manual.index') }}"><strong>Student Manual</strong></a> for official university policies.</p>
                </div>

                <!-- Footer -->
                <div class="text-center mt-5 pt-4 border-top">
                    <p class="text-muted">
                        <i class="fa-solid fa-university me-2"></i>
                        Arellano University — Academic Information System
                    </p>
                    <p class="small text-muted">
                        This help center is maintained to assist students in navigating the AU-AIS portal.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Quick Help Cards — scroll to & open relevant guide
    $('.help-card[data-guide-target]').on('click', function(e) {
        e.preventDefault();
        var targetId = $(this).data('guide-target');
        var $target = $('#' + targetId);

        // Close all open guides first
        $('#accordionGuides .accordion-collapse.show').collapse('hide');

        // Open the target guide after a slight delay
        setTimeout(function() {
            $target.collapse('show');

            // Smooth scroll to it
            $('html, body').animate({
                scrollTop: $target.closest('.accordion-item').offset().top - 100
            }, 500);
        }, 300);
    });
});
</script>
@endsection
