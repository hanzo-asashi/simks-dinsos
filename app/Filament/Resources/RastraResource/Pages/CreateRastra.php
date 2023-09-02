<?php

namespace App\Filament\Resources\RastraResource\Pages;

use App\Filament\Resources\RastraResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRastra extends CreateRecord
{
    protected static string $resource = RastraResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['kabupaten'] = config('custom.default.kodekab');

        return $data;
    }
}
