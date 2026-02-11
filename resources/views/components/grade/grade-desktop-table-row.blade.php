<tr>
    <td class="py-1">
        <div class="fw-semibold">{{ $subjectCode }}</div>
        <div class="small text-muted">{{ $subjectName }}</div>
    </td>
    <td class="py-1"><span class="small">{{ $unitType }}</span></td>
    <td class="py-1 text-center"><span class="small">{{ $creditUnit }}</span></td>
    <td class="py-1"><span class="small">{{ $faculty }}</span></td>
    <td class="py-1 text-center"><span class="small {{ $grade === 'DRP' ? 'text-dark' : ($grade >= 1 && $grade <= 3 ? 'text-success' : 'text-danger') }}">
        {{ 
            $grade
        }}
    </span></td>
</tr>