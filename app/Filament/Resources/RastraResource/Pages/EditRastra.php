<?php

namespace App\Filament\Resources\RastraResource\Pages;

use App\Filament\Resources\RastraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRastra extends EditRecord
{
    protected static string $resource = RastraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
