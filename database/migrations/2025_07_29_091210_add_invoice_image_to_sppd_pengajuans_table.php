<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sppd_pengajuans', function (Blueprint $table) {
            // Menambahkan kolom 'invoice_image' sebagai string nullable
            $table->string('invoice_image')->nullable()->after('estimasi_biaya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sppd_pengajuans', function (Blueprint $table) {
            // Menghapus kolom 'invoice_image' jika migrasi di-rollback
            $table->dropColumn('invoice_image');
        });
    }
};

