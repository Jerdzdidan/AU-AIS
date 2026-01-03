<!-- Desktop Table (visible on tablet and larger) -->
<table class="table mb-0 desktop-table">
    <thead class="table-secondary">
        <tr>
            <th class="fw-bold py-2">Subject</th>
            <th class="fw-bold py-2">Unit Type</th>
            <th class="fw-bold py-2 text-center">Credit Unit</th>
            <th class="fw-bold py-2">Faculty</th>
            <th class="fw-bold py-2 text-center">Grade</th>
        </tr>
    </thead>
    <tbody>
        {{ $slot }}
    </tbody>
</table>
