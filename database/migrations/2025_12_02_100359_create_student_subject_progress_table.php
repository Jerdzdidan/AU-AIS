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

            $table->string('lecture_status')->default("NOT_TAKEN");
            $table->string('laboratory_status')->default("NOT_TAKEN");

            $table->string('lecture_grade')->nullable();
            $table->string('laboratory_grade')->nullable();

            $table->string('grade')->default(null)->nullable();
            $table->string('remarks')->nullable();

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
