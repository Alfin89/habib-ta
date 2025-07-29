<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sppd_pengajuans', function (Blueprint $table) {
            // Kolom untuk tempat berangkat
            $table->string('tempat_berangkat')->after('tempat_kegiatan')->nullable();

            // Kolom untuk dokumen yang diunggah
            // Jika Anda hanya ingin menyimpan nama file/path tunggal:
            $table->string('dokumen_pendukung')->nullable()->after('estimasi_biaya');
            // Jika Anda ingin menyimpan banyak file (menggunakan JSON array):
            // $table->json('dokumen_pendukung')->nullable()->after('estimasi_biaya');
        });
    }

    public function down(): void
    {
        Schema::table('sppd_pengajuans', function (Blueprint $table) {
            $table->dropColumn(['tempat_berangkat', 'dokumen_pendukung']);
        });
    }
};
