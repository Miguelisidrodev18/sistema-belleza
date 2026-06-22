<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_session_id')->constrained()->cascadeOnDelete();
            $table->string('platform', 20)->default('zoom');
            $table->string('meeting_url', 500);
            $table->string('meeting_id', 100)->nullable();
            $table->string('passcode', 100)->nullable();
            $table->string('host_url', 500)->nullable();
            $table->boolean('waiting_room')->default(false);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->string('recording_url', 500)->nullable();
            $table->smallInteger('recording_duration')->unsigned()->nullable(); // segundos
            $table->string('status', 20)->default('pending'); // pending|live|ended|cancelled
            $table->timestamps();

            $table->unique('class_session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
