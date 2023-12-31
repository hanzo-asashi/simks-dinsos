<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class PenerimaManfaatChart extends ChartWidget
{
    protected static ?string $heading = 'Penerima Manfaat Per Bulan';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Beras',
                    'data' => [2433, 3454, 4566, 3300, 5545, 5765, 6787, 8767, 7565, 8576, 9686, 8996],
                    'fill' => 'start',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
