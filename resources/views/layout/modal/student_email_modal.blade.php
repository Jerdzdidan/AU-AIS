<div class="modal fade" id="emailPromptModal" tabindex="-1" aria-labelledby="emailPromptModalLabel" aria-hidden="true"
         data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="emailPromptModalLabel">
              <i class="ri-mail-line me-2"></i>Email Address Required
            </h5>
          </div>
          <div class="modal-body">
            <p class="mb-3">We noticed you don't have an email address on file. Please provide your email to continue.</p>
            <form id="emailPromptForm">
              @csrf
              <div class="mb-0">
                <label for="studentEmailInput" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="studentEmailInput" name="email"
                       placeholder="Enter your email address" required>
                <div class="invalid-feedback" id="emailError"></div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="submitEmailBtn">
              <span class="spinner-border spinner-border-sm d-none me-1" id="emailSpinner" role="status"></span>
              Save Email
            </button>
          </div>
        </div>
      </div>
    </div>