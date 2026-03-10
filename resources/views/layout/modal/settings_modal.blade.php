{{-- Settings Modal --}}
<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="settingsModalLabel">
                    <i class="icon-base bx bx-cog me-2"></i>Account Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                {{-- Email Section (Students Only) --}}
                @if(auth()->user()->user_type === 'STUDENT')
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3"><i class="bx bx-envelope me-1"></i>Update Email</h6>
                    <form id="settingsEmailForm">
                        @csrf
                        <div class="mb-3">
                            <label for="settings_email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="settings_email" name="email"
                                   value="{{ auth()->user()->email }}" placeholder="Enter your email address">
                            <div class="invalid-feedback" id="settings_email_error"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" id="settingsEmailBtn">
                            <span class="spinner-border spinner-border-sm d-none me-1" id="settingsEmailSpinner" role="status"></span>
                            Update Email
                        </button>
                    </form>
                </div>
                <hr>
                @endif

                {{-- Password Section (All Roles) --}}
                <div class="mb-2">
                    <h6 class="fw-semibold mb-3"><i class="bx bx-lock-alt me-1"></i>Change Password</h6>
                    <form id="settingsPasswordForm">
                        @csrf
                        <div class="mb-3">
                            <label for="settings_current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="settings_current_password"
                                   name="current_password" placeholder="Enter current password">
                            <div class="invalid-feedback" id="settings_current_password_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="settings_new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="settings_new_password"
                                   name="new_password" placeholder="Enter new password">
                            <div class="invalid-feedback" id="settings_new_password_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="settings_new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="settings_new_password_confirmation"
                                   name="new_password_confirmation" placeholder="Confirm new password">
                            <div class="invalid-feedback" id="settings_new_password_confirmation_error"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" id="settingsPasswordBtn">
                            <span class="spinner-border spinner-border-sm d-none me-1" id="settingsPasswordSpinner" role="status"></span>
                            Change Password
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

