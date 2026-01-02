
<x-modals.creation-and-update-modal 
    id="add-or-update-modal"
    title="New Data Entry"
    action=""
    submitButtonName="Submit"
>


{{-- student_id and subject_code --}}
<div class="col-sm-12 form-control-validation">
    <x-input.input-field
        id="student_id" 
        name="student_id" 
        label="Student ID"
        type="text"
        icon="fa-solid fa-id-card fa-1x" 
        placeholder="Student ID (e.g. 202012345)" 
        help=""
    />

    <x-input.input-field
        id="subject_code" 
        name="subject_code"
        label="Subject Code"
        type="text"
        icon="fa-solid fa-book fa-1x" 
        placeholder="Subject Code (e.g. CS 101, IT 202)" 
        help=""
    />

    <x-input.input-field
        id="subject_name" 
        name="subject_name"
        label="Subject Name"
        type="text"
        icon="fa-solid fa-book fa-1x" 
        placeholder="Subject Name" 
        help=""
    />
</div>

{{-- Unit Type and Faculty --}}
<div class="col-sm-12 form-control-validation">
    <x-input.input-field
        id="unit_type" 
        name="unit_type" 
        label="Unit Type"
        type="text"
        icon="fa-solid fa-calendar fa-1x" 
        placeholder="Unit Type (e.g. Lecture, Laboratory)" 
        help=""
    />

    <x-input.input-field
        id="faculty" 
        name="faculty"
        label="Faculty"
        type="text"
        icon="fa-solid fa-user fa-1x" 
        placeholder="Faculty (e.g. John Doe)" 
        help=""
    />
</div>

{{-- Credit Unit and Grade --}}
<div class="col-sm-12 form-control-validation">
    <x-input.integer-field 
        id="credit_unit"
        label="Credit Unit"
        icon="fa-solid fa-chalkboard-teacher"
        placeholder="Credit Unit"
        :min="0"
        :step="0.5"
        help=""
    />

    <x-input.integer-field 
        id="grade"
        label="Grade"
        icon="fa-solid fa-chalkboard-teacher"
        placeholder="Grade"
        :min="0"
        :step="0.25"
        help=""
    />
</div>

</x-modals.creation-and-update-modal>