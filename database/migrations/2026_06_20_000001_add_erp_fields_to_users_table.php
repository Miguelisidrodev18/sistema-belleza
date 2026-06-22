<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['administrador', 'docente', 'alumno'])->default('alumno')->after('name');
            $table->string('dni', 15)->nullable()->unique()->after('role');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('photo', 255)->nullable()->after('phone');
            $table->string('address', 500)->nullable()->after('photo');
            $table->date('birth_date')->nullable()->after('address');
            $table->enum('gender', ['M', 'F', 'otro'])->nullable()->after('birth_date');
            $table->boolean('is_active')->default(true)->after('gender');
            $table->softDeletes();

            $table->index('role');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['is_active']);
            $table->dropSoftDeletes();
            $table->dropColumn(['role', 'dni', 'phone', 'photo', 'address', 'birth_date', 'gender', 'is_active']);
        });
    }
};
