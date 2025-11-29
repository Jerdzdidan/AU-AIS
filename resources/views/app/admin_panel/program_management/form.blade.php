
<x-modals.creation-and-update-modal 
    id="add-or-update-modal"
    title="New Program"
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
        icon="fa-solid fa-book fa-1x" 
        placeholder="Name" 
        help=""
    />
</div>

{{-- Code --}}
<div class="col-sm-12 form-control-validation">
    <x-input.input-field
        id="code" 
        name="code" 
        label="Program Code"
        type="text"
        icon="fa-solid fa-book fa-1x" 
        placeholder="Program Code (e.g. BSCS, BSIT)" 
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

{{-- DESCRIPTION --}}
<div class="col-12 form-control-validation">
    <x-input.text-area-field
        id="description"
        icon="fa-solid fa-circle-info fa-1x"
        label="Description"
        placeholder="Enter program description"
        rows="3"
    />
</div>

</x-modals.creation-and-update-modal>