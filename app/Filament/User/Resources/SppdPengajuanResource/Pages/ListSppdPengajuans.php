<?php

namespace App\Filament\User\Resources\SppdPengajuanResource\Pages;

use App\Filament\User\Resources\SppdPengajuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSppdPengajuans extends ListRecords
{
    protected static string $resource = SppdPengajuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
