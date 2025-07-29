<?php

namespace App\Filament\User\Widgets;

use App\Models\SppdPengajuan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MySppdStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        return [
            Stat::make('Total Pengajuan Saya', SppdPengajuan::where('user_id', $userId)->count())
                ->description('Semua pengajuan yang pernah dibuat')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary'),

            Stat::make('Menunggu Persetujuan', SppdPengajuan::where('user_id', $userId)->where('status', 'pending')->count())
                ->description('Sedang diproses admin')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Disetujui', SppdPengajuan::where('user_id', $userId)->where('status', 'approved')->count())
                ->description('Pengajuan yang disetujui')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Draft', SppdPengajuan::where('user_id', $userId)->where('status', 'draft')->count())
                ->description('Belum diajukan')
                ->descriptionIcon('heroicon-o-document')
                ->color('secondary'),
        ];
    }
}
