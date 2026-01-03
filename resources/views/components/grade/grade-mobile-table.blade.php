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
        {{ $slot }}
    </tbody>
</table>