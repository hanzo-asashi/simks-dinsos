<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisBansosResource\Pages;
use App\Filament\Resources\JenisBansosResource\RelationManagers;
use App\Models\JenisBansos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisBansosResource extends Resource
{
    protected static ?string $model = JenisBansos::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->color(fn($record) => $record->warna),
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
            'index' => Pages\ListJenisBansos::route('/'),
            'create' => Pages\CreateJenisBansos::route('/create'),
            'edit' => Pages\EditJenisBansos::route('/{record}/edit'),
        ];
    }
}
