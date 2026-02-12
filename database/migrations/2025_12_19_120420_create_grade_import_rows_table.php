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
        Schema::create('grade_import_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_import_id')->constrained('grade_imports')->cascadeOnDelete();
            $table->string('student_number')->nullable();
            $table->string('subject_code')->nullable();
            $table->string('subject_name')->nullable();
            $table->string('unit_type')->nullable();
            $table->string('school_year')->nullable();
            $table->string('semester')->nullable();
            $table->string('faculty')->nullable();
            $table->decimal('credit_unit', 5, 2)->nullable();
            $table->decimal('grade', 5, 2)->nullable();
            $table->string('validity')->default('invalid');
            $table->string('status')->default('staged');
            $table->json('errors')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_import_rows');
    }
};
