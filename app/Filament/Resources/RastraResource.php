<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RastraResource\Pages;
use App\Filament\Resources\RastraResource\RelationManagers;
use App\Models\Rastra;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
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

class RastraResource extends Resource
{
    protected static ?string $model = Rastra::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $slug = 'rastra';
    protected static ?string $label = 'Rastra';
    protected static ?string $pluralLabel = 'Rastra';

    protected static ?string $recordTitleAttribute = 'nama_penerima';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('dtks_id'),

                TextInput::make('nik')
                    ->required(),

                TextInput::make('nokk')
                    ->required(),

                TextInput::make('nama_penerima')
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

                DatePicker::make('tanggal_terima'),

                FileUpload::make('bukti_foto')
                    ->multiple()
                    ->directory('uploads/bukti')
                    ->image()
                    ->preserveFilenames()
//                    ->minSize(512)
//                    ->maxSize(1024)
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080')
                    ->imagePreviewHeight('100')
//                    ->panelLayout('integrated')
//                    ->panelAspectRatio('2:1')
                ,

                TextInput::make('lokasi')
                    ->nullable(),

                TextInput::make('latitude')
                    ->nullable(),

                TextInput::make('longitude')
                    ->nullable(),

                Select::make('status_rastra')
                    ->options([]),

                TextInput::make('status_dtks')->label('Terdaftar DTKS / NON DTKS'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nik'),

                TextColumn::make('nokk'),

                TextColumn::make('nama_penerima'),

                TextColumn::make('alamat'),

                TextColumn::make('kabupaten'),

                TextColumn::make('kecamatan'),

                TextColumn::make('kelurahan'),

                TextColumn::make('no_urut'),

                TextColumn::make('dtks_id'),

                TextColumn::make('status_rastra'),

                TextColumn::make('tanggal_terima')
                    ->date(),

                TextColumn::make('bukti_foto'),
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
            'index' => Pages\ListRastras::route('/'),
            'create' => Pages\CreateRastra::route('/create'),
            'view' => Pages\ViewRastra::route('/{record}'),
            'edit' => Pages\EditRastra::route('/{record}/edit'),
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
