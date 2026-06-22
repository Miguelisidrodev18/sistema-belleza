<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('version_number')->unsigned();
            $table->text('notes')->nullable();
            $table->boolean('is_current')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['material_id', 'version_number']);
            $table->index('material_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_versions');
    }
};
