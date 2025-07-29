<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->default('user')->after('password');
            $table->string('nip', 20)->nullable()->after('role');
            $table->string('jabatan')->nullable()->after('nip');
            $table->string('unit_kerja')->nullable()->after('jabatan');
            $table->string('phone', 15)->nullable()->after('unit_kerja');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nip', 'jabatan', 'unit_kerja', 'phone']);
        });
    }
};
