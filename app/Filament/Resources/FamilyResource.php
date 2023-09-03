<?php

namespace App\Filament\Resources;

use App\Enums\StatusKeluargaAnggotaEnum;
use App\Filament\Resources\FamilyResource\Pages;
use App\Models\Family;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class FamilyResource extends Resource
{
    protected static ?string $model = Family::class;

    protected static ?string $label = 'Keluarga';

    protected static ?string $slug = 'keluarga';

    protected static ?string $pluralLabel = 'Keluarga';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Shout::make('info')->content('Field bertanda * harus diisi.')->columnSpanFull(),
                \Filament\Forms\Components\Group::make()->schema([
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

                        Select::make('status_kpm')
                            ->label('Status Keluarga Penerima Manfaat (KPM)')
                            ->required()
                            ->options([
                                1 => 'Aktif',
                                0 => 'Non Aktif',
                            ])
                            ->default(1),
                    ])->inlineLabel(),
                ])->columnSpanFull(),

                \Filament\Forms\Components\Group::make()->schema([
                    Section::make('Tambah Anggota Keluarga')->schema([
                        TableRepeater::make('anggota')
                            ->hiddenLabel()
                            ->withoutHeader()
                            ->schema(static::anggotaSchema())
                            ->columns(2)
                            ->relationship('anggota'),
                    ]),
                ])->columnSpanFull(),

            ]);
    }

    public static function anggotaSchema(): array
    {
        return [
            TextInput::make('nik_anggota')
                ->label('NIK Anggota')
                ->required(),
            TextInput::make('nokk_anggota')
                ->label('NO KK Anggota')
                ->required(),
            TextInput::make('nama_anggota')
                ->label('Nama Anggota')
                ->required(),
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
                ->searchable()
                ->options(StatusKeluargaAnggotaEnum::asSelectArray()),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make([
                    Split::make([
                        Grid::make(2)
                            ->schema([
                                Group::make([
                                    TextEntry::make('nik')
                                        ->label('NIK Penerima Manfaat')
                                        ->copyable(),
                                    TextEntry::make('nokk')
                                        ->label('No. KK Penerima Manfaat')
                                        ->copyable(),
                                    TextEntry::make('nama_keluarga')
                                        ->label('Nama Penerima Manfaat'),
                                ]),

                                Group::make([
                                    TextEntry::make('no_telepon')
                                        ->label('No. Telepon / HP'),
                                    TextEntry::make('jenisBansos.short')
                                        ->badge()
                                        ->listWithLineBreaks()
                                        ->limitList(5)
                                        ->label('Jenis Bantuan Sosial'),
                                    TextEntry::make('created_at')
                                        ->label('Published At')
                                        ->badge()
                                        ->date('l, d F Y')
                                        ->color('success'),
                                ]),

                            ]),
                    ])->from('lg'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nik')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->copyable()
                    ->label('NIK Keluarga'),

                TextColumn::make('nokk')
                    ->label('NO. KK Keluarga')
                    ->copyable()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('nama_keluarga')
                    ->label('Nama Keluarga')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('no_telepon')
                    ->label('No. Telepon/HP')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenisBansos.short')
                    ->label('Jenis Bantuan')
                    ->badge()
                    ->searchable()->sortable(),

                TextColumn::make('status_kpm')
                    ->label('Status DTKS')
                    ->alignCenter()
                    ->badge(fn (string $state): string => $state ? 'success' : 'danger')
                    ->color(fn (string $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (string $state) => $state ? 'DTKS' : 'NON DTKS')
                    ->searchable()->sortable(),

            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function () {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),

            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
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
