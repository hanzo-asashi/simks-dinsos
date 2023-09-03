<?php

namespace App\Filament\Resources;

use App\Enums\StatusKeluargaAnggotaEnum;
use App\Filament\Resources\AnggotaResource\Pages;
use App\Models\Anggota;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnggotaResource extends Resource
{
    protected static ?string $model = Anggota::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('dtks_id_anggota')
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('nik_anggota')
                    ->label('NIK Anggota')
                    ->required(),
                TextInput::make('nokk_anggota')
                    ->label('NO KK Anggota')
                    ->required(),
                TextInput::make('nama_anggota')
                    ->label('Nama Anggota')
                    ->required(),
                TextInput::make('no_telp_anggota')
                    ->label('No. Telepon/HP')
                    ->required(),
                Textarea::make('alamat_anggota')
                    ->label('Alamat Anggota')
                    ->required(),
                TextInput::make('kecamatan_anggota')->label('Kecamatan'),
                TextInput::make('kelurahan_anggota')->label('Kelurahan'),
                TextInput::make('kodepos_anggota')->label('Kode POS'),
                Select::make('jenis_bansos_id')
                    ->label('Jenis Bantuan')
                    ->relationship('jenisBantuan', 'nama')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->searchingMessage('Sedang mencari...')
                    ->noSearchResultsMessage('Tidak ada ditemukan.')
                    ->searchDebounce(500)
                    ->createOptionForm([
                        TextInput::make('nama')->required(),
                        TextInput::make('short')->nullable(),
                    ]),
                Select::make('status_keluarga_anggota')
                    ->options(StatusKeluargaAnggotaEnum::asSelectArray()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik_anggota')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('nokk_anggota')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('nama_anggota')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('alamat_anggota')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status_anggota')
                    ->alignCenter()
                    ->label('Status Anggota')
                    ->formatStateUsing(function ($record) {
                        return $record->status_anggota === 1 ? 'AKTIF' : 'NON AKTIF';
                    })
                    ->badge(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger'
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger'
                    }),
                Tables\Columns\TextColumn::make('status_keluarga_anggota')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->striped()
            ->deferLoading()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnggotas::route('/'),
            'create' => Pages\CreateAnggota::route('/create'),
            'edit' => Pages\EditAnggota::route('/{record}/edit'),
        ];
    }
}
