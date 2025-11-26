<div class="table-responsive">
    <table id="{{ $id }}" class="table table-striped table-hover" style="width:100%">
        <thead>
            <tr>
                {{ $slot }}
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via AJAX -->
        </tbody>
    </table>
</div>