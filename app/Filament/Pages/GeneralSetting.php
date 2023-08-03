<?php

namespace App\Filament\Pages;

use App\Services\EnvFileService;
use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class GeneralSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSettings::class;
    protected static bool $shouldRegisterNavigation = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('logo',),
                Forms\Components\FileUpload::make('darklogo',),
                Forms\Components\TextInput::make('favicon'),
                Forms\Components\TextInput::make('kodeprov'),
                Forms\Components\TextInput::make('kodekab'),
                Forms\Components\TextInput::make('kodepos'),
            ]);
    }
}
