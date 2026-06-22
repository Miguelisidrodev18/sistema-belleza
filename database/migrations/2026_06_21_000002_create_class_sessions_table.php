<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained()->nullOnDelete();
            $table->smallInteger('session_number')->unsigned();
            $table->string('title', 200)->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('room', 100)->nullable();
            $table->string('modality', 20)->nullable();
            $table->string('status', 30)->default('scheduled');
            $table->boolean('is_generated')->default(false);
            $table->text('notes')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->timestamps();

            $table->index(['course_section_id', 'starts_at']);
            $table->index('starts_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_sessions');
    }
};
