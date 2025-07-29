<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord; // Ini adalah kelas dasar untuk halaman detail/view

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    // Jika Anda ingin menambahkan aksi atau kustomisasi khusus pada halaman ini,
    // Anda bisa menuliskannya di sini. Misalnya, menambahkan tombol 'Edit':
    // protected function getHeaderActions(): array
    // {
    //     return [
    //         \Filament\Actions\EditAction::make(),
    //     ];
    // }
}
