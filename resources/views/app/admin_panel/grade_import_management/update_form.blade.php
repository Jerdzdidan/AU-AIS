
<x-modals.creation-and-update-modal 
    id="grade-import-update-modal"
    title="Update Grade Import"
    action=""
    submitButtonName="Submit"
    formId="grade-import-update-form"
>

{{-- Name --}}
<div class="col-12 form-control-validation">
    <x-input.input-field
        id="filename" 
        name="filename" 
        label="Filename"
        type="text"
        icon="fa-solid fa-file"
        placeholder="Filename" 
        help=""
    />
</div>

{{-- ACADEMIC PERIOD --}}
<div class="col-12 form-control-validation">
    <x-input.select-field 
        id="academic_period_update_id"
        label="Academic Period"
    />
</div>

</x-modals.creation-and-update-modal>