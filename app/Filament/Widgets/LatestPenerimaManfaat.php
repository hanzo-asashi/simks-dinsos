<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\FamilyResource;
use App\Models\Family;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPenerimaManfaat extends BaseWidget
{
    protected static ?string $heading = 'Penerima Manfaat Terbaru';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(FamilyResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK Keluarga')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nokk')
                    ->label('No KK Keluarga')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_keluarga')
                    ->label('Nama Keluarga')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenisBansos.short')
                    ->alignCenter()
                    ->label('Jenis Bantuan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('status_kpm')
                    ->alignCenter()
                    ->label('Status Penerima')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'AKTIF' : 'NON AKTIF'),
            ])
            ->deferLoading()
            ->striped()
            ->actions([
                Tables\Actions\Action::make('Lihat')
                    ->url(fn (Family $record): string => FamilyResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-magnifying-glass'),
            ]);
    }
}
