
<x-modals.creation-and-update-modal 
    id="add-or-update-modal"
    title="New Officer"
    action=""
    submitButtonName="Submit"
>


{{-- Name --}}
<div class="col-12 form-control-validation">
    <x-input.input-field
        id="name" 
        name="name" 
        label="Name"
        type="text"
        icon="bx bx-user-circle" 
        placeholder="Name" 
        help=""
    />
</div>

{{-- Email and Password --}}
<div class="col-sm-12 form-control-validation">
    <x-input.input-field
        id="email" 
        name="email" 
        label="Email"
        type="text"
        icon="bx bx-id-card" 
        placeholder="Email" 
        help=""
    />
    
    <x-input.password-field
        id="password" 
        name="password" 
        label="Password" 
        icon="bx bx-lock-alt" 
        placeholder="*******"
        help=""
    />
</div>

{{-- DEPARTMENT --}}
<div class="col-12 form-control-validation">
    <x-input.select-field 
        id="department_id"
        label="Department"
    />
</div>

</x-modals.creation-and-update-modal>