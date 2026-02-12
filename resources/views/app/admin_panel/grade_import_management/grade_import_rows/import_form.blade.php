
<x-modals.creation-and-update-modal 
    id="import-modal"
    title="Import Grade Data"
    action=""
    formId="grade-import-form"
    submitButtonName="Import"
>

{{-- FILE --}}
<div class="col-sm-12 form-control-validation">
    <x-input.file-field
        id="grade-file"
        label="Import File"
        name="file"
        accept=".csv,.xlsx,.xls"
        helptext="Upload CSV or Excel files for grade import"
    />
</div>

</x-modals.creation-and-update-modal>