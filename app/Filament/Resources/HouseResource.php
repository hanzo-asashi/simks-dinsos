<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HouseResource\Pages;
use App\Models\House;
use Cheesegrits\FilamentGoogleMaps\Fields\Geocomplete;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use KodePandai\Indonesia\Models\City;
use KodePandai\Indonesia\Models\District;
use KodePandai\Indonesia\Models\Village;

class HouseResource extends Resource
{
    protected static ?string $model = House::class;

    protected static ?string $label = 'Rumah';

    protected static ?string $pluralLabel = 'Rumah';

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->schema([
                    Section::make('')->schema([
                        Grid::make()->schema([
                            FileUpload::make('foto_rumah')
                                ->panelLayout('integrated')
                                ->panelAspectRatio('2:1')
                                ->preserveFilenames()
                                ->image()
                                ->maxSize(2048), FileUpload::make('foto_rumah')
                                ->panelLayout('integrated')
                                ->panelAspectRatio('2:1')
                                ->preserveFilenames()
                                ->image()
                                ->maxSize(2048),
                        ]),

                        Grid::make()->schema([
                            FileUpload::make('foto_rumah')
                                ->panelLayout('integrated')
                                ->panelAspectRatio('2:1')
                                ->preserveFilenames()
                                ->image()
                                ->maxSize(2048),
                        ]),
                    ])->columns(1),
                    Section::make('')->schema([
                        Grid::make(3)->schema([
                            FileUpload::make('foto_rumah')
                                ->panelLayout('integrated')
                                ->panelAspectRatio('2:1')
                                ->preserveFilenames()
                                ->image()
                                ->maxSize(2048), FileUpload::make('foto_rumah')
                                ->panelLayout('integrated')
                                ->panelAspectRatio('2:1')
                                ->preserveFilenames()
                                ->image()
                                ->maxSize(2048),
                        ]),

                        Grid::make()->schema([
                            FileUpload::make('foto_rumah')
                                ->panelLayout('integrated')
                                ->panelAspectRatio('2:1')
                                ->preserveFilenames()
                                ->image()
                                ->maxSize(2048),
                        ]),
                    ])->columns(1),
                ])->columns(2),

                Section::make()->schema([
                    Grid::make()->schema([
                        TextInput::make('nama')
                            ->required(),

                        FileUpload::make('foto_rumah')
                            ->panelLayout('integrated')
                            ->panelAspectRatio('2:1')
                            ->preserveFilenames()
                            ->image()
                            ->maxSize(2048),

                        //                        Geocomplete::make('alamat')
                        //                            ->placeField('location')
                        //                            ->isLocation()
                        //                            ->reverseGeocode([
                        //                                'city' => '%L',
                        //                                'zip' => '%z',
                        //                                'state' => '%A1',
                        //                                'street' => '%n %S',
                        //                            ])
                        //                            ->countries(['id']) // restrict autocomplete results to these countries
                        ////                    ->debug() // output the results of reverse geocoding in the browser console, useful for figuring out symbol formats
                        //                            ->updateLatLng() // update the lat/lng fields on your form when a Place is selected
                        //                            ->maxLength(1024)
                        //                            ->placeholder('Mulai ketik alamat ...')
                        //                            ->geolocate() // add a suffix button which requests and reverse geocodes the device location
                        //                            ->geolocateIcon('heroicon-o-map'), // override the default icon for the geolocate button

                        TextInput::make('no_rumah')
                            ->integer(),

                        TextInput::make('rt')
                            ->nullable(),

                        TextInput::make('rw')
                            ->nullable(),

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
                                    return District::where('city_code', config('custom.default.kodekab'))
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

                        TextInput::make('kodepos')
                            ->default(config('default.kodepos')),

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

                        Select::make('status')
                            ->options([
                                1 => 'Aktif',
                                0 => 'Non Aktif',
                            ])->default(1),

                        TextInput::make('keterangan')
                            ->nullable()
                            ->rules(['max:255']),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('house_uuid')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qrcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_rumah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kodepos')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rt')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rw')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kabupaten')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kecamatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelurahan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lattitude')
                    ->searchable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListHouses::route('/'),
            'create' => Pages\CreateHouse::route('/create'),
            'view' => Pages\ViewHouse::route('/{record}'),
            'edit' => Pages\EditHouse::route('/{record}/edit'),
        ];
    }
}
