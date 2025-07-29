<?php

namespace App\Filament\User\Resources\SppdPengajuanResource\Pages;

use App\Filament\User\Resources\SppdPengajuanResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSppdPengajuan extends ViewRecord
{
    protected static string $resource = SppdPengajuanResource::class;

    // Jika Anda ingin menambahkan aksi di header halaman view (misalnya, tombol edit)
    // Anda bisa mengaktifkan bagian ini:
    // protected function getHeaderActions(): array
    // {
    //     return [
    //         \Filament\Actions\EditAction::make(),
    //     ];
    // }
}
