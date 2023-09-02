<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HouseResource\Pages;
use App\Models\House;
use Cheesegrits\FilamentGoogleMaps\Fields\Geocomplete;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use KodePandai\Indonesia\Models\District;
use KodePandai\Indonesia\Models\Village;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class HouseResource extends Resource
{
    protected static ?string $model = House::class;

    protected static ?string $modelLabel = 'Penerima Manfaat';

    protected static ?string $navigationLabel = 'Penerima Manfaat';

    protected static ?string $slug = 'penerima-manfaat';

    protected static ?string $pluralLabel = 'Penerima Manfaat';

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $recordTitleAttribute = 'family_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Data Keluarga')->schema([
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
                    ]),

                    Section::make('Data Lokasi')->schema([

                        Geocomplete::make('alamat')
                            ->reverseGeocode([
                                'street' => '%n %S',
                                'city' => '%L',
                                'state' => '%A1',
                                'zip' => '%z',
                            ])
                            ->countries(['id'])
                            ->updateLatLng(),

                        Select::make('kecamatan')
                            ->nullable()
                            ->searchable()
                            ->live(true)
                            ->options(function () {
                                $kab = District::query()->where('city_code', config('custom.default.kodekab'));
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
                            ->live(true)
                            ->searchable()
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $village = Village::where('code', $state)->first();
                                if ($village) {
                                    $set('kodepos', $village['postal_code']);
                                    $set('location', [
                                        'lat' => (float) $village['latitude'],
                                        'lng' => (float) $village['longitude'],
                                    ]);
                                }
                            }),

                        TextInput::make('kodepos')
                            ->disabled()->dehydrated()
                            ->default(config('default.kodepos')),

                        TextInput::make('latitude')
                            ->disabled()->dehydrated(),
                        //                            ->live()
                        //                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        //                                $set('location', [
                        //                                    'lat' => (float) $state,
                        //                                    'lng' => (float) $get('longitude'),
                        //                                ]);
                        //                            })
                        //                            ->lazy(),
                        TextInput::make('longitude')
                            ->disabled()->dehydrated(),
                        //                            ->live()
                        //                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        //                                $set('location', [
                        //                                    'lat' => (float) $get('latitude'),
                        //                                    'lng' => (float) $state,
                        //                                ]);
                        //                            })
                        //                            ->lazy(), // important to use lazy, to avoid updates as you type
                    ]),
                ]),

                Group::make([
                    Section::make('Status Penerima Manfaat')->schema([
                        TextInput::make('keterangan')
                            ->nullable()
                            ->rules(['max:255']),

                        Toggle::make('status_rumah')
                            ->label('Status Aktifkan/Non Aktifkan Rumah')
                            ->helperText('Aktif atau Non Aktif Status Rumah')
                            ->default(true),
                    ]),
                    Section::make('Upload Data')->schema([
                        SpatieMediaLibraryFileUpload::make('foto_rumah')
                            ->hiddenLabel()
                            ->preserveFilenames()
                            ->multiple()
                            ->responsiveImages()
                            ->conversion('thumb'),
                    ])
                        ->collapsible(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make([
                    Split::make([
                        Grid::make(2)
                            ->schema([
                                \Filament\Infolists\Components\Group::make([
                                    TextEntry::make('family.nama_keluarga')
                                        ->label('Nama Keluarga')
                                        ->copyable(),
                                    TextEntry::make('family.nik')
                                        ->label('NIK Keluarga')
                                        ->copyable(),
                                    TextEntry::make('family.nama_keluarga')
                                        ->label('Nama Keluarga'),
                                    TextEntry::make('latitude')
                                        ->label('Latitude'),
                                    TextEntry::make('longitude')
                                        ->label('Longitude'),
                                ]),

                                \Filament\Infolists\Components\Group::make([
                                    TextEntry::make('kab.name')
                                        ->label('Kabupaten'),
                                    TextEntry::make('kec.name')
                                        ->label('Kecamatan'),
                                    TextEntry::make('kel.name')
                                        ->label('Kelurahan'),
                                    TextEntry::make('created_at')
                                        ->label('Published At')
                                        ->badge()
                                        ->date('l, d F Y')
                                        ->color('success'),
                                ]),
                            ]),
                    ])->from('lg'),
                ]),
                \Filament\Infolists\Components\Section::make([
                    \Filament\Infolists\Components\Group::make([
                        SpatieMediaLibraryImageEntry::make('foto_rumah'),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('family.nama_keluarga')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable()
                    ->limit('40')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kab.name')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kec.name')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kel.name')
                    ->searchable()
                    ->toggleable()
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Print')
                        ->icon('heroicon-o-printer')
                        ->color('warning')
                        ->url(fn ($record) => route(
                            'filament.admin.resources.penerima-manfaat.print-dokumentasi',
                            $record->id
                        )),
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

    public static function getRecordTitle(?Model $record): string | Htmlable | null
    {
        $keluarga = HouseResource::getEloquentQuery()->first();

        return $keluarga->family->nama_keluarga;
    }

    public static function getRelations(): array
    {
        return [
            AuditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHouses::route('/'),
            'create' => Pages\CreateHouse::route('/create'),
            'view' => Pages\ViewHouse::route('/{record}'),
            'edit' => Pages\EditHouse::route('/{record}/edit'),
            'print-dokumentasi' => Pages\PrintDokumentasi::route('/{record}/print-dokumentasi'),
        ];
    }
}
