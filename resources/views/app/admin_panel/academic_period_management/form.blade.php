
<x-modals.creation-and-update-modal 
    id="add-or-update-modal"
    title="New Academic Period"
    action=""
    submitButtonName="Submit"
>

{{-- Start Year and End Year --}}
<div class="col-sm-12 form-control-validation">
    <x-input.integer-field 
        id="year_start"
        label="Start Year"
        icon="fa-solid fa-calendar"
        placeholder="Start-Year (e.g. 2023)"
        help=""
    />

    <x-input.integer-field 
        id="year_end"
        label="End Year"
        icon="fa-solid fa-calendar"
        placeholder="End-Year (e.g. 2024)"
        help=""
    />
</div>

{{-- Semester --}}
<div class="col-sm-12 form-control-validation">
    <x-input.select-field
        id="semester"
        label="Semester"
        icon="fa-solid fa-tags"
        :options="[
            ['value' => '1st', 'text' => '1st Semester'],
            ['value' => '2nd', 'text' => '2nd Semester'],
            ['value' => 'SUMMER', 'text' => 'Summer'],
        ]"
        placeholder="Select Category"
    />
</div>

</x-modals.creation-and-update-modal>