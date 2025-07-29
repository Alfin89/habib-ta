<?php

namespace App\Filament\User\Resources\SppdPengajuanResource\Pages;

use App\Filament\User\Resources\SppdPengajuanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSppdPengajuan extends CreateRecord
{
    protected static string $resource = SppdPengajuanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['status'] = 'draft';

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
