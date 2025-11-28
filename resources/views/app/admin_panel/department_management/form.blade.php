
<x-modals.creation-and-update-modal 
    id="add-or-update-modal"
    title="New Department"
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
        icon="bx bx-building" 
        placeholder="Name" 
        help=""
    />
</div>

{{-- Code --}}
<div class="col-sm-12 form-control-validation">
    <x-input.input-field
        id="code" 
        name="code" 
        label="Department Code"
        type="text"
        icon="bx bx-building" 
        placeholder="Department Code (e.g. SCS, SHTM)" 
        help=""
    />
</div>

{{-- Department Head --}}
<div class="col-sm-12 form-control-validation">
    <x-input.input-field
        id="head_of_department" 
        name="head_of_department" 
        label="Head of Department"
        type="text"
        icon="bx bx-user" 
        placeholder="Head of Department" 
        help=""
    />
</div>

</x-modals.creation-and-update-modal>