<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('enrollment_number', 20)->unique();
            $table->foreignId('alumno_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_period_id')->constrained()->cascadeOnDelete();
            $table->string('status', 30)->default('activa');
            $table->date('enrolled_at');
            $table->date('withdrawn_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['alumno_id', 'course_section_id']);
            $table->index('academic_period_id');
            $table->index(['alumno_id', 'academic_period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
