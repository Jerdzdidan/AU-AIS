
@php
    if ($grade == -1) {
        $gradeClass = 'text-dark';
    } elseif ($grade >= 1 && $grade <= 3) {
        $gradeClass = 'text-success';
    } else {
        $gradeClass = 'text-danger';
    }
@endphp
@php
    if ($grade == -1)
    {
        $grade_display = 'DRP';
    }
    else if ($grade == 0)
    {
        $grade_display = 'INC';
    }
    else
    {
        $grade_display = $grade;
    }
@endphp
<tr class="subject-row" onclick="toggleDetails(this)">
    <td class="py-2 text-center">
        <span class="toggle-icon">›</span>
    </td>
    <td class="py-2">
        <div class="fw-semibold">{{ $subjectCode }}</div>
        <div class="small text-muted">{{ $subjectName }}</div>
    </td>
    <td class="py-2 text-center {{ $gradeClass }}">
        <span>{{ $grade_display }}</span>
    </td>
</tr>
<tr class="details-row">
    <td colspan="3" class="p-0">
        <div class="details-content">
            <div class="detail-item">
                <span class="detail-label">Unit Type:</span>
                <span class="detail-value">{{ $unitType }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Credit Unit:</span>
                <span class="detail-value">{{ $creditUnit }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Faculty:</span>
                <span class="detail-value">{{ $faculty }}</span>
            </div>
        </div>
    </td>
</tr>
