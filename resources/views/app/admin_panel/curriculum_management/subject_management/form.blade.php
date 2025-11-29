
<x-modals.creation-and-update-modal 
    id="add-or-update-modal"
    title="New Subject"
    action=""
    submitButtonName="Submit"
>


{{-- Subject Code and Name --}}
<div class="col-sm-12 form-control-validation">
    <x-input.input-field
        id="code" 
        name="code" 
        label="Subject Code"
        type="text"
        icon="fa-solid fa-book fa-1x" 
        placeholder="Subject Code (e.g. CS101, IT202)" 
        help=""
    />

    <x-input.input-field
        id="name" 
        name="name"
        label="Subject Name"
        type="text"
        icon="fa-solid fa-book fa-1x" 
        placeholder="Subject Name" 
        help=""
    />
</div>

{{-- Year Level and Category --}}
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

    <x-input.select-field
        id="semester"
        label="Semester"
        icon="fa-solid fa-tags"
        :options="[
            ['value' => 'FIRST', 'text' => 'First'],
            ['value' => 'SECOND', 'text' => 'Second'],
        ]"
        placeholder="Select Semester"
    />

    <x-input.select-field
        id="subject_category"
        label="Subject Category"
        icon="fa-solid fa-tags"
        :options="[
            ['value' => 'MAJOR', 'text' => 'MAJOR'],
            ['value' => 'MINOR', 'text' => 'MINOR'],
        ]"
        placeholder="Select Category"
    />
</div>

{{-- Lecture and Laboratory Units --}}
<div class="col-sm-12 form-control-validation">
    <x-input.integer-field 
        id="lec_units"
        label="Lecture Units"
        icon="fa-solid fa-chalkboard-teacher"
        placeholder="Lecture Units"
        :min="0"
        :step="0.5"
        help=""
    />
    <x-input.integer-field 
        id="lab_units"
        label="Laboratory Units"
        icon="fa-solid fa-flask"
        placeholder="Laboratory Units"
        :min="0"
        :step="0.5"
        help=""
    />
</div>

{{-- Prerequisites --}}
<div class="col-sm-12 form-control-validation">
    <x-input.text-area-field
        id="prerequisites"
        icon="fa-solid fa-circle-info fa-1x"
        label="Prerequisites"
        placeholder="Enter curriculum prerequisites"
        rows="3"
    />
</div>

</x-modals.creation-and-update-modal>