<?php

namespace App\Filament\Widgets;

use App\Models\Family;
use App\Models\House;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PenerimaManfaatOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        return [
            Stat::make('Penerima Manfaat', House::count())
                ->description('Jumlah Total Penerima Manfaat')
//                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Penerima RASTRA Baru', House::query()->where('created_at', today())->count())
                ->description('Total Penerima RASTRA Baru Hari Ini')
//                ->descriptionIcon('heroicon-m-arrow-trending-down')
//                ->chart([17, 16, 14, 15, 14, 13, 12])
                ->color('danger'),
            Stat::make('Penerima RASTRA Baru ', House::query()->where('created_at', now()->month)->count())
                ->description('Total Penerima RASTRA Baru Bulan Ini')
//                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('info'),
            Stat::make('Keluarga', Family::query()->count())
                ->description('Total Keluarga')
//                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('warning'),
        ];
    }
}
