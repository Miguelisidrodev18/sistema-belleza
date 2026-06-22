<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_period_id')->constrained()->cascadeOnDelete();
            $table->string('section_code', 10)->default('A');
            $table->smallInteger('capacity')->default(30);
            $table->string('status', 30)->default('draft');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['course_id', 'academic_period_id', 'section_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_sections');
    }
};
