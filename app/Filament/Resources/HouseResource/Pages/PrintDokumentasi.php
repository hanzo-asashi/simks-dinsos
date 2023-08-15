<?php

namespace App\Filament\Resources\HouseResource\Pages;

use App\Filament\Resources\HouseResource;
use Filament\Resources\Pages\Page;

class PrintDokumentasi extends Page
{
    protected static string $resource = HouseResource::class;

    protected static string $view = 'filament.resources.house-resource.pages.print-dokumentasi';
}
