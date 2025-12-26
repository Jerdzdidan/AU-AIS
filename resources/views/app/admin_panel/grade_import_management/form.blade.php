<!-- Updated admin_creation_modal.html -->
{{-- <div class="offcanvas offcanvas-end" id="add-or-update-modal">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="ModalLabel">Import Grade</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <label for="grades-dropzone">Import File: </label>
        <form class="dropzone needsclick mt-2" id="grades-dropzone" action="">
            @csrf

            <div class="dz-message needsclick">
                Drop files here or click to upload
                <span class="note needsclick">(Upload CSV or Excel files for grade import)</span>
            </div>
        </form>
        <!-- Form Actions -->
        <div class="col-sm-12 pt-2">
            <div class="d-flex gap-2">
                <button type="button" id="submit-upload" class="btn btn-primary data-submit flex-fill">
                    Submit
                </button>
                <button type="reset" class="btn btn-outline-secondary flex-fill" data-bs-dismiss="offcanvas">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div> --}}

<!-- Updated form -->
<div class="offcanvas offcanvas-end" id="add-or-update-modal">
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
</div>