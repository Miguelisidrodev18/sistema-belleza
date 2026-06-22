<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('absent');
            $table->time('arrival_time')->nullable();
            $table->time('departure_time')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamp('recorded_at')->useCurrent();
            // no timestamps() — recorded_at serves as created_at

            $table->unique(['class_session_id', 'enrollment_id']);
            $table->index('class_session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
