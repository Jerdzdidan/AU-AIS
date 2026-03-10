@extends('layout.base')

@section('title')
FAQs
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
    <style>
        .faq-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .faq-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eaeaea;
        }
        .faq-header h2 {
            font-weight: 700;
            color: #2c3e50;
        }
        .faq-content {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }

        /* Search Bar */
        .faq-search-wrapper {
            position: relative;
            max-width: 600px;
            margin: 0 auto 35px auto;
        }
        .faq-search-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9e9e9e;
            font-size: 1rem;
        }
        .faq-search-input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 0.95rem;
            transition: border-color 0.3s, box-shadow 0.3s;
            outline: none;
        }
        .faq-search-input:focus {
            border-color: #1855a9;
            box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.15);
        }
        .faq-search-input::placeholder {
            color: #bbb;
        }

        /* Category Section */
        .faq-category {
            margin-bottom: 30px;
        }
        .faq-category-title {
            color: #2c3e50;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1855a9;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .faq-category-title i {
            color: #1855a9;
            font-size: 1.2rem;
        }

        /* Accordion Customization */
        .faq-accordion .accordion-item {
            border: 1px solid #eaeaea;
            border-radius: 8px !important;
            margin-bottom: 10px;
            overflow: hidden;
            transition: box-shadow 0.3s;
        }
        .faq-accordion .accordion-item:hover {
            box-shadow: 0 2px 8px rgba(105, 108, 255, 0.1);
        }
        .faq-accordion .accordion-button {
            font-weight: 600;
            font-size: 0.92rem;
            color: #2c3e50;
            padding: 14px 20px;
            background-color: #fafafa;
            border: none;
        }
        .faq-accordion .accordion-button:not(.collapsed) {
            background-color: #f0f0ff;
            color: #1855a9;
            box-shadow: none;
        }
        .faq-accordion .accordion-button:focus {
            box-shadow: none;
        }
        .faq-accordion .accordion-button::after {
            transition: transform 0.3s ease;
        }
        .faq-accordion .accordion-body {
            margin-top: 9px;
            font-size: 0.9rem;
            color: #555;
            line-height: 1.7;
            padding: 16px 20px;
        }
        .faq-accordion .accordion-body ul {
            padding-left: 20px;
            margin-bottom: 0;
        }
        .faq-accordion .accordion-body ul li {
            margin-bottom: 6px;
        }

        /* No Results */
        .faq-no-results {
            display: none;
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        .faq-no-results i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
            color: #ccc;
        }

        /* Info Box */
        .info-box {
            background: #e8f4fc;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 5px 5px 0;
        }

        /* Result Counter */
        .faq-result-count {
            text-align: center;
            margin-bottom: 20px;
            color: #999;
            font-size: 0.85rem;
            display: none;
        }
    </style>
@endsection

@section('nav_title')
FAQs
@endsection

@section('body')
<div class="container-fluid">
    <div class="content-container">
        <div class="faq-container">
            <div class="faq-content">
                <!-- FAQ Header -->
                <div class="faq-header">
                    <h2><i class="fa-solid fa-circle-question me-2"></i>Frequently Asked Questions</h2>
                    <p class="text-muted">Find quick answers to the most common questions about the AU Academic Information System.</p>
                </div>

                <!-- Search Bar -->
                <div class="faq-search-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="faq-search-input" id="faqSearchInput" placeholder="Search for a question..." autocomplete="off">
                </div>

                <!-- Result Count -->
                <div class="faq-result-count" id="faqResultCount"></div>

                <!-- ===================== ACCOUNT & ACCESS ===================== -->
                <div class="faq-category" data-category="account">
                    <h4 class="faq-category-title">
                        <i class="fa-solid fa-user-shield"></i> Account & Access
                    </h4>
                    <div class="accordion faq-accordion" id="accordionAccount">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-acc-1">
                                    How do I log in to the Student Portal?
                                </button>
                            </h2>
                            <div id="faq-acc-1" class="accordion-collapse collapse" data-bs-parent="#accordionAccount">
                                <div class="accordion-body">
                                    To log in, go to the AU-AIS login page and select <strong>"Student Login"</strong>. Enter your student ID number and password provided by the university. If you are a first-time user, your default password is typically your student ID number.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-acc-2">
                                    I forgot my password. What should I do?
                                </button>
                            </h2>
                            <div id="faq-acc-2" class="accordion-collapse collapse" data-bs-parent="#accordionAccount">
                                <div class="accordion-body">
                                    If you have forgotten your password, please contact the <strong>IT Help Desk</strong> at your branch to request a password reset. Make sure to bring a valid school ID for verification.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-acc-3">
                                    How do I update my email address?
                                </button>
                            </h2>
                            <div id="faq-acc-3" class="accordion-collapse collapse" data-bs-parent="#accordionAccount">
                                <div class="accordion-body">
                                    If you have not yet set your email, the system will automatically prompt you with a modal dialog upon login. Simply enter your email address and click <strong>"Save Email"</strong>. If you need to change your email or password later, you can do so from your <strong>My Profile</strong> page.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-acc-4">
                                    Can I access the portal on my mobile device?
                                </button>
                            </h2>
                            <div id="faq-acc-4" class="accordion-collapse collapse" data-bs-parent="#accordionAccount">
                                <div class="accordion-body">
                                    Yes! The AU Academic Information System is fully responsive and can be accessed from any device with a web browser, including smartphones and tablets. Simply visit the portal URL on your mobile browser and log in as usual.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================== ACADEMIC PROGRESS ===================== -->
                <div class="faq-category" data-category="progress">
                    <h4 class="faq-category-title">
                        <i class="fa-solid fa-user-graduate"></i> Academic Progress
                    </h4>
                    <div class="accordion faq-accordion" id="accordionProgress">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-prog-1">
                                    How do I check my academic progress?
                                </button>
                            </h2>
                            <div id="faq-prog-1" class="accordion-collapse collapse" data-bs-parent="#accordionProgress">
                                <div class="accordion-body">
                                    Navigate to <strong>"Academic Progress"</strong> in the sidebar under Academic Information. This page shows your curriculum checklist with all required subjects, your completion status, and overall progress percentage. Subjects are color-coded:
                                    <ul>
                                        <li><strong>Green</strong> — Passed / Completed</li>
                                        <li><strong>Red</strong> — Failed</li>
                                        <li><strong>Yellow</strong> — In Progress</li>
                                        <li><strong>Gray</strong> — Not Yet Taken</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-prog-2">
                                    Can I download my academic progress report?
                                </button>
                            </h2>
                            <div id="faq-prog-2" class="accordion-collapse collapse" data-bs-parent="#accordionProgress">
                                <div class="accordion-body">
                                    Yes. On the Academic Progress page, click the <strong>"Download PDF"</strong> button located at the top of the page. This will generate and download a PDF copy of your current academic progress checklist for your records.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-prog-3">
                                    Why does my progress show a subject as "Not Yet Taken" even though I already enrolled?
                                </button>
                            </h2>
                            <div id="faq-prog-3" class="accordion-collapse collapse" data-bs-parent="#accordionProgress">
                                <div class="accordion-body">
                                    Subject statuses are updated based on your official grade records. If your grade has not been submitted or committed by the registrar yet, the subject will still appear as "Not Yet Taken." Please allow time for grades to be processed and contact the Registrar's Office if the issue persists.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================== GRADES ===================== -->
                <div class="faq-category" data-category="grades">
                    <h4 class="faq-category-title">
                        <i class="fa-solid fa-star"></i> Grades
                    </h4>
                    <div class="accordion faq-accordion" id="accordionGrades">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-grade-1">
                                    How do I view my grades?
                                </button>
                            </h2>
                            <div id="faq-grade-1" class="accordion-collapse collapse" data-bs-parent="#accordionGrades">
                                <div class="accordion-body">
                                    Click <strong>"Grades"</strong> in the sidebar under Academic Information. Your grades are organized by academic period, showing each subject's code, name, units, instructor, and your final grade. Your General Weighted Average (GWA) for each period is also displayed.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-grade-2">
                                    How is my GWA (General Weighted Average) computed?
                                </button>
                            </h2>
                            <div id="faq-grade-2" class="accordion-collapse collapse" data-bs-parent="#accordionGrades">
                                <div class="accordion-body">
                                    Your GWA is calculated by multiplying each subject's grade by its credit units, summing the products, and dividing by the total credit units. Only subjects with numerical grades are included in the computation. Subjects marked as "INC", "W", or "DRP" are excluded.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-grade-3">
                                    My grade seems incorrect. What should I do?
                                </button>
                            </h2>
                            <div id="faq-grade-3" class="accordion-collapse collapse" data-bs-parent="#accordionGrades">
                                <div class="accordion-body">
                                    If you believe a grade is incorrect, please follow these steps:
                                    <ul>
                                        <li>Contact your <strong>subject instructor</strong> first to verify the grade.</li>
                                        <li>If there is a discrepancy, ask your instructor to coordinate with the <strong>IT Help Desk</strong> for a grade correction.</li>
                                        <li>Grade corrections must be processed officially and may take several working days to reflect in the system.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-grade-4">
                                    Why does it say "No grade records as of yet"?
                                </button>
                            </h2>
                            <div id="faq-grade-4" class="accordion-collapse collapse" data-bs-parent="#accordionGrades">
                                <div class="accordion-body">
                                    This message appears when no grades have been imported and committed for your student record yet. Grades are uploaded and processed by the Registrar's Office each academic period. Please check back after the official grade release date or contact the Registrar for more details.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================== ENROLLMENT & REGISTRATION ===================== -->
                <div class="faq-category" data-category="enrollment">
                    <h4 class="faq-category-title">
                        <i class="fa-solid fa-clipboard-list"></i> Enrollment & Registration
                    </h4>
                    <div class="accordion faq-accordion" id="accordionEnrollment">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-enr-1">
                                    How do I enroll for the upcoming semester?
                                </button>
                            </h2>
                            <div id="faq-enr-1" class="accordion-collapse collapse" data-bs-parent="#accordionEnrollment">
                                <div class="accordion-body">
                                    Enrollment is handled through the <strong>Office of the Registrar</strong>. Please refer to the official enrollment schedule announced through your university email or the AU homepage announcements. Visit your designated enrollment center on your scheduled date with required documents.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-enr-2">
                                    Can I add or drop subjects after enrollment?
                                </button>
                            </h2>
                            <div id="faq-enr-2" class="accordion-collapse collapse" data-bs-parent="#accordionEnrollment">
                                <div class="accordion-body">
                                    Yes, adding or dropping of subjects is allowed within the first two weeks of the semester (subject to university policy). You must fill out the official Add/Drop form at the Registrar's Office and secure the approval of your academic adviser and department head. Please refer to the <strong>Student Manual</strong> for detailed procedures.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-enr-3">
                                    Where can I find the academic calendar?
                                </button>
                            </h2>
                            <div id="faq-enr-3" class="accordion-collapse collapse" data-bs-parent="#accordionEnrollment">
                                <div class="accordion-body">
                                    The academic calendar is usually posted on the official Arellano University website and announced through email. You can also inquire at the <strong>Office of the Registrar</strong> or check the <strong>homepage announcements</strong> upon logging in to the portal.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================== GENERAL ===================== -->
                <div class="faq-category" data-category="general">
                    <h4 class="faq-category-title">
                        <i class="fa-solid fa-circle-info"></i> General
                    </h4>
                    <div class="accordion faq-accordion" id="accordionGeneral">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-gen-1">
                                    What is the AU Academic Information System (AU-AIS)?
                                </button>
                            </h2>
                            <div id="faq-gen-1" class="accordion-collapse collapse" data-bs-parent="#accordionGeneral">
                                <div class="accordion-body">
                                    The AU-AIS is an online platform designed for Arellano University students to conveniently access their academic records. Through this system, you can view your grades, track your academic progress, access the student manual, and stay updated with university announcements — all in one place.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-gen-2">
                                    Who do I contact for technical issues with the portal?
                                </button>
                            </h2>
                            <div id="faq-gen-2" class="accordion-collapse collapse" data-bs-parent="#accordionGeneral">
                                <div class="accordion-body">
                                    For any technical issues such as page errors, login problems, or display issues, please contact the <strong>IT Help Desk</strong> at your branch campus. You can also visit the <strong>Help</strong> page in this portal for more contact information and troubleshooting guides.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-gen-3">
                                    Where can I read the official Student Manual?
                                </button>
                            </h2>
                            <div id="faq-gen-3" class="accordion-collapse collapse" data-bs-parent="#accordionGeneral">
                                <div class="accordion-body">
                                    The Student Manual is available in the sidebar under <strong>Student Information → Manual</strong>. It contains the official policies, rules, and regulations of Arellano University. The manual is for viewing purposes only.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No Results Message -->
                <div class="faq-no-results" id="faqNoResults">
                    <i class="fa-solid fa-face-meh"></i>
                    <h5>No matching questions found</h5>
                    <p>Try using different keywords or browse the categories above.</p>
                </div>

                <!-- Info Box -->
                <div class="info-box mt-4">
                    <h5><i class="fa-solid fa-circle-info me-2"></i>Can't find your answer?</h5>
                    <p class="mb-0">If your question is not listed here, please visit the <a href="{{ route('student.help.index') }}"><strong>Help</strong></a> page for step-by-step guides and contact information, or reach out to the Office of the Registrar at your respective branch campus.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var $searchInput = $('#faqSearchInput');
    var $categories = $('.faq-category');
    var $noResults = $('#faqNoResults');
    var $resultCount = $('#faqResultCount');

    $searchInput.on('keyup', function() {
        var query = $(this).val().toLowerCase().trim();

        if (query === '') {
            // Show everything when search is cleared
            $categories.show();
            $categories.find('.accordion-item').show();
            $noResults.hide();
            $resultCount.hide();
            return;
        }

        var totalVisible = 0;

        $categories.each(function() {
            var $cat = $(this);
            var catVisible = 0;

            $cat.find('.accordion-item').each(function() {
                var $item = $(this);
                var question = $item.find('.accordion-button').text().toLowerCase();
                var answer = $item.find('.accordion-body').text().toLowerCase();

                if (question.indexOf(query) !== -1 || answer.indexOf(query) !== -1) {
                    $item.show();
                    catVisible++;
                    totalVisible++;
                } else {
                    $item.hide();
                }
            });

            // Hide entire category if no items match
            $cat.toggle(catVisible > 0);
        });

        if (totalVisible === 0) {
            $noResults.show();
            $resultCount.hide();
        } else {
            $noResults.hide();
            $resultCount.text(totalVisible + ' question' + (totalVisible !== 1 ? 's' : '') + ' found').show();
        }
    });
});
</script>
@endsection
