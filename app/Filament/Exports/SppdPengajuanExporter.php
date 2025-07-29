<?php

namespace App\Filament\Exports;

use App\Models\SppdPengajuan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;
use OpenSpout\Common\Entity\Style\Style;
use Carbon\Carbon; // Pastikan Carbon diimport

class SppdPengajuanExporter extends Exporter
{
    // Model yang akan diekspor
    protected static ?string $model = SppdPengajuan::class;

    // Mendefinisikan kolom-kolom yang akan diekspor
    public static function getColumns(): array
    {
        return [
            // Kolom ID
            ExportColumn::make('id')
                ->label('ID'),
            // Kolom user_id, tampilkan nama pengguna dari relasi 'user'
            ExportColumn::make('user.name')
                ->label('Nama Pengaju'),
            // Kolom nomor_surat
            ExportColumn::make('nomor_surat')
                ->label('Nomor Surat'),
            // Kolom nama_kegiatan
            ExportColumn::make('nama_kegiatan')
                ->label('Nama Kegiatan'),
            // Kolom deskripsi_kegiatan, batasi panjang teks
            ExportColumn::make('deskripsi_kegiatan')
                ->label('Deskripsi Kegiatan')
                ->limit(100), // Batasi hingga 100 karakter
            // Kolom tempat_kegiatan
            ExportColumn::make('tempat_kegiatan')
                ->label('Tempat Kegiatan'),
            // Kolom tempat_berangkat
            ExportColumn::make('tempat_berangkat')
                ->label('Tempat Berangkat'),
            // Kolom tanggal_mulai, format tanggal
            ExportColumn::make('tanggal_mulai')
                ->label('Tanggal Mulai')
                ->formatStateUsing(fn (Carbon $state): string => $state->translatedFormat('d F Y')),
            // Kolom tanggal_selesai, format tanggal
            ExportColumn::make('tanggal_selesai')
                ->label('Tanggal Selesai')
                ->formatStateUsing(fn (Carbon $state): string => $state->translatedFormat('d F Y')),
            // Kolom waktu_kegiatan, format waktu
            ExportColumn::make('waktu_kegiatan')
                ->label('Waktu Kegiatan')
                ->formatStateUsing(fn (Carbon $state): string => $state->translatedFormat('H:i')),
            // Kolom status
            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))), // Format status agar lebih mudah dibaca
            // Kolom catatan_admin, batasi panjang teks
            ExportColumn::make('catatan_admin')
                ->label('Catatan Admin')
                ->limit(100),
            // Kolom estimasi_biaya, format sebagai mata uang
            ExportColumn::make('estimasi_biaya')
                ->label('Estimasi Biaya')
                ->formatStateUsing(fn (float $state): string => 'Rp ' . number_format($state, 2, ',', '.')),
            // Kolom dokumen_pendukung, tampilkan sebagai URL jika ada
            ExportColumn::make('dokumen_pendukung')
                ->label('Dokumen Pendukung')
                ->formatStateUsing(function (?string $state): string {
                    return $state ? url('storage/' . $state) : 'Tidak ada'; // Asumsi dokumen disimpan di storage
                }),
            // Kolom approved_by, tampilkan nama pengguna dari relasi 'approvedBy'
            ExportColumn::make('approvedBy.name')
                ->label('Disetujui Oleh'),
            // Kolom approved_at, format tanggal dan waktu
            ExportColumn::make('approved_at')
                ->label('Waktu Disetujui')
                ->formatStateUsing(fn (?Carbon $state): string => $state ? $state->translatedFormat('d F Y H:i') : 'Belum Disetujui'),
            // Kolom created_at
            ExportColumn::make('created_at')
                ->label('Dibuat Pada')
                ->formatStateUsing(fn (Carbon $state): string => $state->translatedFormat('d F Y H:i')),
            // Kolom updated_at
            ExportColumn::make('updated_at')
                ->label('Diperbarui Pada')
                ->formatStateUsing(fn (Carbon $state): string => $state->translatedFormat('d F Y H:i')),
        ];
    }

    // Metode ini dipanggil setelah ekspor selesai.
    // Anda bisa menambahkan logika notifikasi atau lainnya di sini.
    // Misalnya, mengirim notifikasi ke admin bahwa ekspor telah selesai.
    public function afterExport(Export $export): void
    {
        // Contoh: Anda bisa menambahkan notifikasi atau log di sini.
        // \Illuminate\Support\Facades\Log::info("Ekspor SPPD Pengajuan selesai: {$export->getKey()}");
    }

    // Mengubah query sebelum ekspor
    // Anda bisa menambahkan filter atau eager loading di sini
    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['user', 'approvedBy']); // Eager load relasi untuk menghindari N+1 query
    }

    // Mengatur nama file ekspor
    public function getFileName(Export $export): string
    {
        return "sppd-pengajuan-{$export->getKey()}.csv"; // Nama file default
    }

    // Mengatur disk penyimpanan file ekspor
    public function getFileDisk(): string
    {
        // Gunakan disk 'local' untuk file ekspor agar tidak dapat diakses publik secara langsung
        // Atau 's3' dengan kebijakan akses pribadi di lingkungan produksi
        return 'local';
    }

    // Mengatur gaya untuk sel XLSX (opsional)
    public function getXlsxCellStyle(): ?Style
    {
        return (new Style())
            ->setFontSize(10)
            ->setFontName('Arial');
    }

    // Mengatur gaya untuk sel header XLSX (opsional)
    public function getXlsxHeaderCellStyle(): ?Style
    {
        return (new Style())
            ->setFontBold()
            ->setFontSize(11)
            ->setFontName('Arial');
    }

    // Metode notifikasi yang sudah ada dari generate awal
    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor pengajuan SPPD Anda telah selesai dan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal diekspor.';
        }

        return $body;
    }
}
