<?php

namespace App\Filament\Admin\Resources\SppdPengajuanResource\Pages;

use App\Filament\Admin\Resources\SppdPengajuanResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSppdPengajuan extends ViewRecord
{
    protected static string $resource = SppdPengajuanResource::class;

    // Jika Anda ingin menambahkan aksi atau kustomisasi khusus untuk halaman view,
    // Anda bisa menuliskannya di sini. Contoh:
    // protected function getHeaderActions(): array
    // {
    //     return [
    //         // Tambahkan aksi seperti EditAction di halaman View
    //         \Filament\Actions\EditAction::make(),
    //     ];
    // }
}
