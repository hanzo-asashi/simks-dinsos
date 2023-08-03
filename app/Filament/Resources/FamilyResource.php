<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyResource\Pages;
use App\Filament\Resources\FamilyResource\RelationManagers;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use KodePandai\Indonesia\Models\City;
use KodePandai\Indonesia\Models\District;
use KodePandai\Indonesia\Models\Village;

class FamilyResource extends Resource
{
    protected static ?string $model = Family::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('dtks_id')
                        ->maxLength(36)
                        ->default(\Str::orderedUuid()),

                    TextInput::make('nik')
                        ->maxLength(16)
                        ->required(),

                    TextInput::make('nokk')
                        ->maxLength(16)
                        ->required(),

                    TextInput::make('nama')
                        ->required(),

                    TextInput::make('no_telepon')
                        ->maxLength(14)
                        ->required(),

                    Textarea::make('alamat')
                        ->autosize()
                        ->rows(1)
                        ->required(),

                    Select::make('kabupaten')
                        ->nullable()
                        ->options(
                            City::where('province_code', config('default.kodeprov'))
                                ->pluck('name', 'code')
                        )
                        ->afterStateUpdated(fn(callable $set) => $set('kecamatan', null))
                        ->reactive()
                        ->searchable(),

                    Select::make('kecamatan')
                        ->nullable()
                        ->searchable()
                        ->reactive()
                        ->options(function (callable $get) {
                            $kab = District::query()->where('city_code', $get('kabupaten'));
                            if (!$kab) {
                                return District::where('city_code', config('default.kodepkab'))
                                    ->pluck('name', 'code');
                            }

                            return $kab->pluck('name', 'code');
                        })
                        ->hidden(fn(callable $get) => !$get('kabupaten'))
                        ->afterStateUpdated(fn(callable $set) => $set('kelurahan', null)),

                    Select::make('kelurahan')
                        ->nullable()
                        ->options(function (callable $get) {
                            $kel = Village::query()->where('district_code', $get('kecamatan'));
                            if (!$kel) {
                                return Village::where('district_code', '731211')
                                    ->pluck('name', 'code');
                            }
                            return $kel->pluck('name', 'code');
                        })
                        ->reactive()
                        ->searchable()
                        ->hidden(fn(callable $get) => !$get('kecamatan'))
                        ->afterStateUpdated(function (callable $set, $state) {
                            $village = Village::where('code', $state)->first();
                            if ($village) {
                                $set('latitude', $village['latitude']);
                                $set('longitude', $village['longitude']);
                                $set('kode_pos', $village['postal_code']);
                                $set('location', [
                                    'lat' => (float) $village['latitude'],
                                    'lng' => (float) $village['longitude'],
                                ]);
                            }

                        }),
                    TextInput::make('kode_pos')
                        ->maxLength(6),

                    Select::make('jenis_bansos_id')
                        ->label('Jenis Bantuan Sosial')
                        ->searchable()
                        ->multiple()
                        ->preload()
                        ->relationship('jenisBansos', 'nama'),

                    Select::make('status_kpm')
                        ->label('Status Keluarga Penerima Manfaat (KPM)')
                        ->required()
                        ->options([
                            1 => 'Aktif',
                            0 => 'Non Aktif'
                        ]),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dtks_id')->searchable()->sortable(),

                TextColumn::make('nik')->searchable()->sortable(),

                TextColumn::make('nokk')->searchable()->sortable(),

                TextColumn::make('nama')->searchable()->sortable(),

                TextColumn::make('alamat')->searchable()->sortable(),

                TextColumn::make('kabupaten')->searchable()->sortable(),

                TextColumn::make('kecamatan')->searchable()->sortable(),

                TextColumn::make('kelurahan')->searchable()->sortable(),

                TextColumn::make('no_rumah'),

                TextColumn::make('no_telepon')->searchable()->sortable(),

                TextColumn::make('jenis_bansos_id')->searchable()->sortable(),

                TextColumn::make('status_kpm')->searchable()->sortable(),

                TextColumn::make('kode_pos')->searchable()->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListFamilies::route('/'),
            'create' => Pages\CreateFamily::route('/create'),
            'view' => Pages\ViewFamily::route('/{record}'),
            'edit' => Pages\EditFamily::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
