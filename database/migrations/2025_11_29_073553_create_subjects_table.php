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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->constrained();
            $table->string('code');
            $table->string('name');
            $table->integer('year_level')->nullable();
            $table->string('semester')->nullable();
            $table->string('subject_category');
            $table->decimal('lec_units', 4, 2);
            $table->decimal('lab_units', 4, 2);
            $table->text('prerequisites')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
