<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perintah Perjalanan Dinas (SPPD)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        .content {
            margin-top: 20px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>Surat Perintah Perjalanan Dinas (SPPD)</h1>
    <div class="content">
        <p><strong>Nomor Surat:</strong> {{ $sppd->nomor_surat ?? 'Belum ada' }}</p>
        <p><strong>Pengaju:</strong> {{ $sppd->user->name }}</p>
        <p><strong>Nama Kegiatan:</strong> {{ $sppd->nama_kegiatan }}</p>
        <p><strong>Deskripsi Kegiatan:</strong> {{ $sppd->deskripsi_kegiatan ?? '-' }}</p>
        <p><strong>Tempat Kegiatan:</strong> {{ $sppd->tempat_kegiatan }}</p>
        <p><strong>Tanggal Mulai:</strong> {{ $sppd->tanggal_mulai->format('d-m-Y') }}</p>
        <p><strong>Tanggal Selesai:</strong> {{ $sppd->tanggal_selesai->format('d-m-Y') }}</p>
        <p><strong>Waktu Kegiatan:</strong> {{ $sppd->waktu_kegiatan ? $sppd->waktu_kegiatan->format('H:i') : '-' }}</p>
        <p><strong>Estimasi Biaya:</strong> Rp {{ number_format($sppd->estimasi_biaya, 2, ',', '.') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($sppd->status) }}</p>
        @if ($sppd->approvedBy)
            <p><strong>Disetujui Oleh:</strong> {{ $sppd->approvedBy->name }}</p>
            <p><strong>Tanggal Persetujuan:</strong> {{ $sppd->approved_at->format('d-m-Y H:i') }}</p>
        @endif
        @if ($sppd->catatan_admin)
            <p><strong>Catatan Admin:</strong> {{ $sppd->catatan_admin }}</p>
        @endif
    </div>
    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>
    </div>
</body>
</html>
