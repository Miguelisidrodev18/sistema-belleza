<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('current_period_id')
                ->nullable()
                ->constrained('academic_periods')
                ->nullOnDelete();
            $table->smallInteger('default_capacity')->default(30);
            $table->boolean('allow_overbooking')->default(false);
            $table->string('default_timezone', 50)->default('America/Lima');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_settings');
    }
};
