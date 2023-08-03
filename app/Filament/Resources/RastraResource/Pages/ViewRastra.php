<?php

namespace App\Filament\Resources\RastraResource\Pages;

use App\Filament\Resources\RastraResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRastra extends ViewRecord
{
    protected static string $resource = RastraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
