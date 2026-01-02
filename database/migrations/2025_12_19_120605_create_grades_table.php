<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('subject_id')->nullable();
            $table->string('subject_code')->nullable();
            $table->string('subject_name')->nullable();
            $table->string('unit_type')->nullable();
            $table->string('school_year');
            $table->string('semester');
            $table->string('faculty')->nullable();
            $table->decimal('credit_unit', 5, 2);
            $table->decimal('grade', 5, 2);
            $table->foreignId('grade_import_id')->nullable()->constrained('grade_imports')->nullOnDelete();
            $table->timestamps();

            // Prevents duplicate grades for same student/subject/period
            $table->unique(['student_id', 'subject_id', 'school_year', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
