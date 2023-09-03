<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnggota extends CreateRecord
{
    protected static string $resource = AnggotaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['jenis_bansos_id'] = 1;

        return $data;
    }
}
