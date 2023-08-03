<?php
declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SystemSettings extends Settings
{
    public string $logo;
    public string $favicon;
    public string $darklogo;
    public string $kodepos;
    public string $kodekab;


    public static function group(): string
    {
        return 'system-settings';
    }
}
