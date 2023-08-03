<?php

use App\Settings\GeneralSettings;

function getSettings($key)
{
    return app(GeneralSettings::class)->$key ?? null;
}


function getSelected(): string
{
    if (request()?->routeIs('users.*')) {
        return 'tab_two';
    }

    if (request()?->routeIs('permissions.*')) {
        return 'tab_three';
    }

    if (request()?->routeIs('roles.*')) {
        return 'tab_three';
    }

    if (request()?->routeIs('database-backups.*')) {
        return 'tab_four';
    }

    if (request()?->routeIs('general-settings.*')) {
        return 'tab_five';
    }

    if (request()?->routeIs('dashboards.*')) {
        return 'tab_one';
    }

    return 'tab_one';
}
