<?php

namespace App\Filament\Resources\JenisBansosResource\Pages;

use App\Filament\Resources\JenisBansosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisBansos extends ListRecords
{
    protected static string $resource = JenisBansosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
