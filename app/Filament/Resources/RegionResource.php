<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegionResource\Pages;
use App\Models\House;
use App\Models\Region;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use KodePandai\Indonesia\Models\City;
use KodePandai\Indonesia\Models\District;
use KodePandai\Indonesia\Models\Village;

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make('house_id')
                        ->options(House::pluck('alamat', 'id'))
                        ->searchable(),
                    Select::make('kabupaten')
                        ->nullable()
                        ->options(
                            City::where('province_code', config('custom.default.kodeprov'))
                                ->pluck('name', 'code')
                        )
                        ->afterStateUpdated(fn (callable $set) => $set('kecamatan', null))
                        ->reactive()
                        ->searchable(),

                    Select::make('kecamatan')
                        ->nullable()
                        ->searchable()
                        ->reactive()
                        ->options(function (callable $get) {
                            $kab = District::query()->where('city_code', $get('kabupaten'));
                            if (! $kab) {
                                return District::where('city_code', config('custom.default.kodepkab'))
                                    ->pluck('name', 'code');
                            }

                            return $kab->pluck('name', 'code');
                        })
                        ->afterStateUpdated(fn (callable $set) => $set('kelurahan', null)),

                    Select::make('kelurahan')
                        ->nullable()
                        ->options(function (callable $get) {
                            $kel = Village::query()->where('district_code', $get('kecamatan'));
                            if (! $kel) {
                                return Village::where('district_code', '731211')
                                    ->pluck('name', 'code');
                            }

                            return $kel->pluck('name', 'code');
                        })
                        ->reactive()
                        ->searchable()
                        ->afterStateUpdated(function (callable $set, $state) {
                            $village = Village::where('code', $state)->first();
                            if ($village) {
                                $set('latitude', $village['latitude']);
                                $set('longitude', $village['longitude']);
                                $set('location', [
                                    'lat' => (float) $village['latitude'],
                                    'lng' => (float) $village['longitude'],
                                ]);
                            }

                        }),
                    TextInput::make('latitude')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $set('location', [
                                'lat' => (float) $state,
                                'lng' => (float) $get('longitude'),
                            ]);
                        })
                        ->lazy(), // important to use lazy, to avoid updates as you type
                    TextInput::make('longitude')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $set('location', [
                                'lat' => (float) $get('latitude'),
                                'lng' => (float) $state,
                            ]);
                        })
                        ->lazy(), // important to use lazy, to avoid updates as you type
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_wilayah'),

                TextColumn::make('kabupaten'),

                TextColumn::make('kecamatan'),

                TextColumn::make('kelurahan'),

                TextColumn::make('latitude'),

                TextColumn::make('longitude'),

                TextColumn::make('house_id'),

                TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListRegions::route('/'),
            'create' => Pages\CreateRegion::route('/create'),
            'view' => Pages\ViewRegion::route('/{record}'),
            'edit' => Pages\EditRegion::route('/{record}/edit'),
        ];
    }
}
