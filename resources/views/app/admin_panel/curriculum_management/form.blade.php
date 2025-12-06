
<x-modals.creation-and-update-modal 
    id="add-or-update-modal"
    title="New Curriculum"
    action=""
    submitButtonName="Submit"
>


{{-- Program --}}
<div class="col-12 form-control-validation">
    <x-input.select-field 
        id="program_id"
        label="Program"
        placeholder="Select a program"
    />
</div>

<div class="col-sm-12 form-control-validation">
    <x-input.integer-field 
        id="year_start"
        label="Year Start"
        icon="fa-solid fa-calendar"
        placeholder="Year-Start (e.g. 2023)"
        help=""
    />

    <x-input.integer-field 
        id="year_end"
        label="Year End"
        icon="fa-solid fa-calendar"
        placeholder="Year-End (e.g. 2025)"
        help=""
    />
</div>

{{-- Description --}}
<div class="col-sm-12 form-control-validation">
    <x-input.text-area-field
        id="description"
        icon="fa-solid fa-circle-info fa-1x"
        label="Description"
        placeholder="Enter curriculum description"
        rows="3"
    />
</div>

</x-modals.creation-and-update-modal>