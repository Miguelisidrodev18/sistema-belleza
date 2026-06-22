<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('class_session_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('visibility', 20)->default('section');
            $table->boolean('is_published')->default(false);
            $table->tinyInteger('order')->unsigned()->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index('course_section_id');
            $table->index('class_session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
