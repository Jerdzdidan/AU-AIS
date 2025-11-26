<x-modals.creation-and-update-modal 
    id = "AddModal"
    title = "New Admin"
    action = ""
    submitButtonName = "Submit"
>

{{-- Email and Password --}}
<div class="col-sm-12 form-control-validation">
    <x-input.input-field
        id="email" 
        name="email" 
        label="Email"
        type="text"
        icon="bx bx-id-card" 
        placeholder="Email" 
    />
    
    <x-input.password-field
        id="password" 
        name="password" 
        label="Password" 
        icon="bx bx-lock-alt" 
        placeholder="*******"
        help="Leave blank to use default password (Arellano@1938)"
    />
</div>

{{-- Name --}}
<div class="col-12 form-control-validation">
    <x-input.input-field
        id="name" 
        name="name" 
        label="Name"
        type="text"
        icon="bx bx-user-circle" 
        placeholder="Username" 
    />
</div>


</x-modals.creation-and-update-modal>