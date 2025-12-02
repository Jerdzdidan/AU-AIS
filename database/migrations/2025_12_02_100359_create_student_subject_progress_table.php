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
        Schema::create('student_subject_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');

            $table->boolean('lecture_completed')->default(false);
            $table->boolean('laboratory_completed')->default(false);

            $table->decimal('lecture_grade', 3, 2)->nullable();
            $table->decimal('laboratory_grade', 3, 2)->nullable();

            $table->string('semester_taken')->nullable();
            $table->year('year_taken')->nullable();

            $table->timestamps();

            $table->unique(['student_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_subject_progress');
    }
};
