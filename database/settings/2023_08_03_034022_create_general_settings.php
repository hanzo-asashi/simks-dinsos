<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general-settings.logo','/assets/images/logo.png');
        $this->migrator->add('general-settings.darklogo','/assets/images/darklogo.png');
        $this->migrator->add('general-settings.favicon','/assets/images/favicon.png');
        $this->migrator->add('general-settings.kodeprov','73');
        $this->migrator->add('general-settings.kodekab', '7312');
        $this->migrator->add('general-settings.kodepos', '90861');
    }
};
