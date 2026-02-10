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
        Schema::create('student_import_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_import_id')->constrained('student_imports')->cascadeOnDelete();
            $table->string('student_number')->nullable();
            $table->string('name')->nullable();
            $table->string('program_code')->nullable();
            $table->string('program_name')->nullable();
            $table->integer('year_level')->nullable();
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
        Schema::dropIfExists('student_import_rows');
    }
};
