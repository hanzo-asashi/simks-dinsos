<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.settings';
    protected static ?string $navigationGroup = 'Settings';

    public function goToUserSetting()
    {
        return $this->redirect('users');
    }

    public function goToGeneralSetting()
    {
        return $this->redirect('general-setting');
    }

}
