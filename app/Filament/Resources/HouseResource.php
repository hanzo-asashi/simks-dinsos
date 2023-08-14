<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HouseResource\Pages;
use App\Models\House;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use KodePandai\Indonesia\Models\District;
use KodePandai\Indonesia\Models\Village;

class HouseResource extends Resource
{
    protected static ?string $model = House::class;

    protected static ?string $label = 'Alamat Penerima Manfaat';

    protected static ?string $pluralLabel = 'Alamat Penerima Manfaat';

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make()->schema([
                        Select::make('family_id')
                            ->label('Keluarga')
                            ->lazy()
                            ->searchable()
                            ->preload()
                            ->optionsLimit(10)
                            ->relationship('family', 'nama_keluarga')
                            ->required()
                            ->createOptionForm([
                                Section::make([
                                    TextInput::make('nik')
                                        ->label('Nomor Induk Kependudukan (NIK)')
                                        ->maxLength(16)
                                        ->required(),

                                    TextInput::make('nokk')
                                        ->label('Nomor Kartu Keluarga (KK)')
                                        ->maxLength(16)
                                        ->required(),

                                    TextInput::make('nama_keluarga')
                                        ->label('Nama Keluarga')
                                        ->required(),

                                    TextInput::make('no_telepon')
                                        ->maxLength(14)
                                        ->required(),

                                    Select::make('jenis_bansos_id')
                                        ->label('Jenis Bantuan Sosial')
                                        ->relationship('jenisBansos', 'nama')
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

                                    Toggle::make('status_kpm')->default(true),
                                ])->columns(2),
                            ]),
                        Textarea::make('alamat')->nullable(),

                        Select::make('kecamatan')
                            ->nullable()
                            ->searchable()
                            ->live(true)
                            ->options(function () {
                                $kab = District::query()->where('city_code', config('custom.default.kodekab'));
                                if (!$kab) {
                                    return District::where('city_code', config('custom.default.kodekab'))
                                        ->pluck('name', 'code');
                                }

                                return $kab->pluck('name', 'code');
                            })
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
                            ->live(true)
                            ->searchable()
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $village = Village::where('code', $state)->first();
                                if ($village) {
                                    $set('latitude', $village['latitude']);
                                    $set('longitude', $village['longitude']);
                                    $set('kodepos', $village['postal_code']);
                                    $set('location', [
                                        'lat' => (float) $village['latitude'],
                                        'lng' => (float) $village['longitude'],
                                    ]);
                                }
                            }),

                        TextInput::make('kodepos')
                            ->hidden()
                            ->default(config('default.kodepos')),

                        TextInput::make('latitude')
                            ->hidden()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $set('location', [
                                    'lat' => (float) $state,
                                    'lng' => (float) $get('longitude'),
                                ]);
                            })
                            ->lazy(), // important to use lazy, to avoid updates as you type
                        TextInput::make('longitude')
                            ->hidden()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $set('location', [
                                    'lat' => (float) $get('latitude'),
                                    'lng' => (float) $state,
                                ]);
                            })
                            ->lazy(), // important to use lazy, to avoid updates as you type

                        TextInput::make('keterangan')
                            ->nullable()
                            ->rules(['max:255']),

                        Toggle::make('status_rumah')
                            ->helperText('Aktif atau Non Aktif')
                            ->default(true),
                    ])->inlineLabel(),

                    Section::make('Attachment')->schema([
                        SpatieMediaLibraryFileUpload::make('foto_rumah')
                            ->preserveFilenames()
                            ->multiple()
                            ->responsiveImages()
                            ->conversion('thumb'),
                    ])
                        ->collapsible(),
                ])->columnSpan(['lg' => fn(?House $record) => $record === null ? 3 : 2]),
            ])
            ->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('family_id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kabupaten')
                    ->searchable()
                    ->sortable()
                    ->toggleable(true),
                Tables\Columns\TextColumn::make('kecamatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kelurahan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('status_rumah')
                    ->boolean(),
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
