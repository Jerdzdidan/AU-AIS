@extends('layout.base')

@section('title')
Grades
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/app/admin_panel/user_management/custom_profile.css') }}">
    <style>
        /* Collapsible row styles */
        .subject-row {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .subject-row:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .toggle-icon {
            display: inline-block;
            transition: transform 0.3s ease;
            font-weight: bold;
            color: #0d6efd;
        }
        
        .toggle-icon.rotated {
            transform: rotate(90deg);
        }
        
        .details-row {
            display: none;
            background-color: #f8f9fa;
        }
        
        .details-row.show {
            display: table-row;
        }
        
        .details-content {
            padding: 1rem;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }
        
        .detail-value {
            font-weight: 500;
            color: #212529;
        }
        
        /* Desktop view - show full table */
        @media (min-width: 769px) {
            .mobile-collapsible {
                display: none;
            }
            
            .desktop-table {
                display: table;
            }
        }
        
        /* Mobile view - show collapsible version */
        @media (max-width: 768px) {
            .mobile-collapsible {
                display: table;
            }
            
            .desktop-table {
                display: none;
            }
            
            .card-header h5 {
                font-size: 0.95rem;
            }
            
            .card-footer h6 {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .card-header h5 {
                font-size: 0.85rem;
                line-height: 1.3;
            }
        }
    </style>
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
        <div class="card">
            <!-- Card Header -->
            <div class="card-header bg-primary py-3 py-md-4">
                <h5 class="mb-0 text-white fw-bold">School Year 2023-2024 | 1st Semester</h5>
            </div>

            <!-- Card Body -->
            <div class="card-body px-0 py-0">
                
                <!-- Desktop Table (visible on tablet and larger) -->
                <table class="table mb-0 desktop-table">
                    <thead class="table-secondary">
                        <tr>
                            <th class="fw-bold py-2 text-center" colspan="2">Subject</th>
                            <th class="fw-bold py-2">Unit Type</th>
                            <th class="fw-bold py-2 text-center">Credit Unit</th>
                            <th class="fw-bold py-2">Faculty</th>
                            <th class="fw-bold py-2 text-center">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-1"><span class="small fw-semibold">CS101</span></td>
                            <td class="py-1"><span class="small">Introduction to Programming</span></td>
                            <td class="py-1"><span class="small">Lecture</span></td>
                            <td class="py-1 text-center"><span class="small">3</span></td>
                            <td class="py-1"><span class="small">Dr. John Smith</span></td>
                            <td class="py-1 text-center"><span class="small">1.25</span></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Mobile Collapsible Table (visible on mobile) -->
                <table class="table mb-0 mobile-collapsible">
                    <thead class="table-secondary">
                        <tr>
                            <th class="fw-bold py-2" style="width: 10px;"></th>
                            <th class="fw-bold py-2">Subject</th>
                            <th class="fw-bold py-2 text-center">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Subject 1 -->
                        <tr class="subject-row" onclick="toggleDetails(this)">
                            <td class="py-2 text-center">
                                <span class="toggle-icon">›</span>
                            </td>
                            <td class="py-2">
                                <div class="fw-semibold">CS101</div>
                                <div class="small text-muted">Introduction to Programming</div>
                            </td>
                            <td class="py-2 text-center">
                                <span>1.25</span>
                            </td>
                        </tr>
                        <tr class="details-row">
                            <td colspan="3" class="p-0">
                                <div class="details-content">
                                    <div class="detail-item">
                                        <span class="detail-label">Unit Type:</span>
                                        <span class="detail-value">Lecture</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Credit Unit:</span>
                                        <span class="detail-value">3</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Faculty:</span>
                                        <span class="detail-value">Dr. John Smith</span>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
                
            </div>

            <!-- Card Footer -->
            <div class="card-footer bg-secondary py-2 py-md-3">
                <div class="row h-100 g-2">
                    <div class="col-12 col-md-6 d-flex align-items-center justify-content-start">
                            <small class="text-white">General Weighted Average (GWA):</small>
                            <span class="text-white fw-semibold px-2 border-bottom border-1 border-white">
                                <small>3.67</small>
                            </span>
                    </div>
                    <div class="col-12 col-md-6 d-flex align-items-center justify-content-start justify-content-md-end">
                            <small class="text-white">Total Credit Units:</small>
                            <span class="text-white fw-semibold px-2 border-bottom border-1 border-white">
                                <small>10.00</small>
                            </span>
                    </div>
                </div>
            </div>
        </div>

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