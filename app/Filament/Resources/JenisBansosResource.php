<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisBansosResource\Pages;
use App\Models\JenisBansos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JenisBansosResource extends Resource
{
    protected static ?string $model = JenisBansos::class;

    protected static ?string $label = 'Jenis Bansos';

    protected static ?string $navigationLabel = 'Jenis Bansos';

    protected static ?string $navigationGroup = 'Master';

    protected static ?string $pluralLabel = 'Jenis Bansos';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('warna'),
                Forms\Components\TextInput::make('short')
                    ->maxLength(15),
                Forms\Components\Textarea::make('deskripsi')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Jenis Bantuan')
                    ->limit(30)
                    ->searchable()
                    ->sortable(),
                //                Tables\Columns\TextColumn::make('warna'),
                Tables\Columns\BadgeColumn::make('short')
                    ->label('Alias')
                    ->color(fn ($record) => $record->warna),
                //                Tables\Columns\TextColumn::make('short')
                //                    ->searchable()->sortable()
                //                    ->label('Alias'),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi Bantuan')
                    ->limit(50),
            ])
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
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListJenisBansos::route('/'),
            'create' => Pages\CreateJenisBansos::route('/create'),
            'edit' => Pages\EditJenisBansos::route('/{record}/edit'),
        ];
    }
}
