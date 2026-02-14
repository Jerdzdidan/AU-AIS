<tr>
    <td class="py-1">
        <div class="fw-semibold">{{ $subjectCode }}</div>
        <div class="small text-muted">{{ $subjectName }}</div>
    </td>
    <td class="py-1"><span class="small">{{ $unitType }}</span></td>
    <td class="py-1 text-center"><span class="small">{{ $creditUnit }}</span></td>
    <td class="py-1"><span class="small">{{ $faculty }}</span></td>
    <td class="py-1 text-center">
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
        <span class="small {{ $gradeClass }}">
            {{ $grade_display }}
        </span>
    </td>
</tr>
