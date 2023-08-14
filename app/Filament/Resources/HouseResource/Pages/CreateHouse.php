<?php

namespace App\Filament\Resources\HouseResource\Pages;

use App\Filament\Resources\HouseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHouse extends CreateRecord
{
    protected static string $resource = HouseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['kabupaten'] = '7312';

        return $data;
    }
}
