<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_version_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20)->default('other');
            $table->string('title', 200)->nullable();
            $table->string('original_name', 255)->nullable();
            $table->string('disk', 50)->default('local');
            $table->string('path', 500);
            $table->string('mime_type', 100)->nullable();
            $table->bigInteger('size_bytes')->unsigned()->nullable();
            $table->tinyInteger('sort')->unsigned()->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('material_version_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_attachments');
    }
};
