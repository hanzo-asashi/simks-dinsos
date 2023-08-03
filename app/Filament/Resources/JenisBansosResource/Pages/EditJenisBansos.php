<?php

namespace App\Filament\Resources\JenisBansosResource\Pages;

use App\Filament\Resources\JenisBansosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisBansos extends EditRecord
{
    protected static string $resource = JenisBansosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
