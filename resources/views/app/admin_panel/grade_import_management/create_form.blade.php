{{-- <div class="offcanvas offcanvas-end" id="add-or-update-modal">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="ModalLabel">Import Grade</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form id="grade-import-form" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="grade-file" class="form-label">Import File</label>
                <input type="file" 
                       class="form-control" 
                       id="grade-file" 
                       name="file" 
                       accept=".csv,.xlsx,.xls"
                       required>
                <div class="form-text">Upload CSV or Excel files for grade import</div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="fa-solid fa-upload me-2"></i>
                    Upload & Import
                </button>
                <button type="button" class="btn btn-outline-secondary flex-fill" data-bs-dismiss="offcanvas">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div> --}}

<x-modals.creation-and-update-modal 
    id="grade-import-create-modal"
    title="New Grade Import"
    action=""
    submitButtonName="Submit"
    formId="grade-import-create-form"
    enctype="multipart/form-data"
>

<div class="mb-3">
    <x-input.file-field
        id="grade-file"
        label="Import File"
        name="file"
        accept=".csv,.xlsx,.xls"
        helptext="Upload CSV or Excel files for grade import"
    />
</div>

{{-- ACADEMIC PERIOD --}}
<div class="col-12 form-control-validation">
    <x-input.select-field 
        id="academic_period_id"
        label="Academic Period"
    />
</div>

</x-modals.creation-and-update-modal>