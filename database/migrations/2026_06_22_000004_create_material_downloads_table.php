<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_attachment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('alumno_id')->constrained('users');
            $table->timestamp('downloaded_at')->useCurrent();
            $table->string('ip_address', 45)->nullable();

            $table->index('material_attachment_id');
            $table->index('alumno_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_downloads');
    }
};
