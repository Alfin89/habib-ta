<?php

namespace App\Filament\Admin\Resources\SppdPengajuanResource\Pages;

use App\Filament\Admin\Resources\SppdPengajuanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSppdPengajuan extends EditRecord
{
    protected static string $resource = SppdPengajuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
