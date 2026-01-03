<div class="card">
    <!-- Card Header -->
    <div class="card-header bg-primary py-3 py-md-4">
        <h5 class="mb-0 text-white fw-bold">{{ $academicPeriod }}</h5>
    </div>

    <!-- Card Body -->
    <div class="card-body px-0 py-0">
        
        {{ $slot }}
        
    </div>

    <!-- Card Footer -->
    <div class="card-footer bg-secondary py-2 py-md-3">
        <div class="row h-100 g-2">
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-start">
                    <small class="text-white">General Weighted Average (GWA):</small>
                    <span class="text-white fw-semibold px-2 border-bottom border-1 border-white">
                        <small>{{ $gwa }}</small>
                    </span>
            </div>
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-start justify-content-md-end">
                    <small class="text-white">Total Credit Units:</small>
                    <span class="text-white fw-semibold px-2 border-bottom border-1 border-white">
                        <small>{{ $totalUnits }}</small>
                    </span>
            </div>
        </div>
    </div>
</div>