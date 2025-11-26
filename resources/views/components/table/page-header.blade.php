<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="page-title"> {{ $title ?? '' }}</h2>
            <p class="page-subtitle">{{ $subtitle ?? '' }}</p>
        </div>
        <div class="col-md-4 text-end">
            {{-- BUTTONS --}}
            {{ $slot }}
        </div>
    </div>
</div>