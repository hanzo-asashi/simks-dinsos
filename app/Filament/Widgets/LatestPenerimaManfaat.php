<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\FamilyResource;
use App\Models\Family;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPenerimaManfaat extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(FamilyResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nik')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nokk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_keluarga')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_kpm')
                    ->label('Status Penerima')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Non Aktif'),
                Tables\Columns\TextColumn::make('jenisBansos.short')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->listWithLineBreaks(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->url(fn (Family $record): string => FamilyResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
