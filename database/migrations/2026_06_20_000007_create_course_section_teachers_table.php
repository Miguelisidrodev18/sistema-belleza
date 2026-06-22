<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_section_teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('role', 50)->default('principal');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['course_section_id', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_section_teachers');
    }
};
