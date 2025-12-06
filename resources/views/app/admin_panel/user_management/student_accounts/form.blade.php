
<x-modals.creation-and-update-modal 
    id="add-or-update-modal"
    title="New Officer"
    action=""
    submitButtonName="Submit"
>

{{-- Student Number and Name --}}
<div class="col-12 form-control-validation">
    <x-input.input-field
        id="student_number" 
        name="student_number" 
        label="Student Number"
        type="text"
        icon="bx bx-id-card" 
        placeholder="Student Number" 
        help=""
    />

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
    <x-input.password-field
        id="password" 
        name="password" 
        label="Password" 
        icon="bx bx-lock-alt" 
        placeholder="*******"
        help=""
    />
</div>

{{-- Year Level --}}
<div class="col-sm-12 form-control-validation">
    <x-input.integer-field 
        id="year_level"
        label="Year Level"
        icon="fa-solid fa-graduation-cap"
        placeholder="1-4"
        :min="1"
        :max="4"
        help=""
    />
</div>


{{-- PROGRAM --}}
<div class="col-12 form-control-validation">
    <x-input.select-field 
        id="program_id"
        label="Program"
    />
</div>

{{-- CURRICULUM --}}
<div class="col-12 form-control-validation">
    <x-input.select-field 
        id="curriculum_id"
        label="Curriculum"
        prop="disabled"
        placeholder="Select a curriculum"
    />
</div>

</x-modals.creation-and-update-modal>