<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SppdPengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nomor_surat',
        'nama_kegiatan',
        'deskripsi_kegiatan',
        'tempat_kegiatan',
        'tempat_berangkat', // <-- Tambahkan ini
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_kegiatan',
        'status',
        'catatan_admin',
        'estimasi_biaya',
        'invoice_image', // <-- Tambahkan ini
        'dokumen_pendukung', // <-- Tambahkan ini
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'waktu_kegiatan' => 'datetime',
        'estimasi_biaya' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Generate nomor surat otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->nomor_surat)) {
                $model->nomor_surat = 'SPPD/' . date('Y') . '/' . str_pad(
                    static::whereYear('created_at', date('Y'))->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}
