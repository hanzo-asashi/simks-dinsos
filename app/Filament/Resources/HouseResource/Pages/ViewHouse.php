<?php

namespace App\Filament\Resources\HouseResource\Pages;

use App\Filament\Resources\HouseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewHouse extends ViewRecord
{
    protected static string $resource = HouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
