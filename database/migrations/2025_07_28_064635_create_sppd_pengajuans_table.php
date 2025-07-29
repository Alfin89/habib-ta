<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sppd_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nomor_surat')->nullable()->unique();
            $table->string('nama_kegiatan');
            $table->text('deskripsi_kegiatan')->nullable();
            $table->string('tempat_kegiatan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('waktu_kegiatan')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'completed'])->default('draft');
            $table->text('catatan_admin')->nullable();
            $table->decimal('estimasi_biaya', 15, 2)->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('tanggal_mulai');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sppd_pengajuans');
    }
};
