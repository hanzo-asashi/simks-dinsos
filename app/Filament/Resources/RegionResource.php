<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegionResource\Pages;
use App\Models\House;
use App\Models\Region;
use Cheesegrits\FilamentGoogleMaps\Fields\Geocomplete;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('house_id')
                    ->options(House::pluck('nama', 'id'))
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
                    ->options(function (callable $get, callable $set) {
                        $kel = Village::query()->where('district_code', $get('kecamatan'));
                        if (! $kel) {
                            return Village::where('district_code', '731211')
                                ->pluck('name', 'code');
                        }

                        return $kel->pluck('name', 'code');
                    })
                    ->reactive()
                    ->searchable()
                    ->afterStateUpdated(function (callable $set, callable $get, $state) {
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
                //                Geocomplete::make('location')
                //                    ->placeField('nama_wilayah')
                //                    ->isLocation()
                //                    ->reverseGeocode([
                //                        'city' => '%L',
                //                        'zip' => '%z',
                //                        'state' => '%A1',
                //                        'street' => '%n %S',
                //                    ])
                //                    ->countries(['id']) // restrict autocomplete results to these countries
                //                    ->debug() // output the results of reverse geocoding in the browser console, useful for figuring out symbol formats
                //                    ->updateLatLng() // update the lat/lng fields on your form when a Place is selected
                //                    ->maxLength(1024)
                //                    ->placeholder('Mulai ketik alamat ...')
                //                    ->geolocate() // add a suffix button which requests and reverse geocodes the device location
                //                    ->geolocateIcon('heroicon-o-map'), // override the default icon for the geolocate button
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
