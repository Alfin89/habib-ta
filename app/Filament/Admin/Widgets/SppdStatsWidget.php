<?php

namespace App\Filament\Admin\Widgets;

use App\Models\SppdPengajuan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SppdStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengajuan', SppdPengajuan::count())
                ->description('Semua pengajuan SPPD')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary'),

            Stat::make('Menunggu Persetujuan', SppdPengajuan::where('status', 'pending')->count())
                ->description('Perlu review admin')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Disetujui', SppdPengajuan::where('status', 'approved')->count())
                ->description('Pengajuan disetujui')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Total Estimasi Biaya', 'Rp ' . number_format(SppdPengajuan::where('status', 'approved')->sum('estimasi_biaya'), 0, ',', '.'))
                ->description('Biaya yang disetujui')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('info'),
        ];
    }
}
