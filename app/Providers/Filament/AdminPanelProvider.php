<?php

namespace App\Providers\Filament;

//use App\Filament\Widgets\DashboardMultiWidget;
use App\Filament\Widgets\LatestPenerimaManfaat;
use App\Filament\Widgets\PenerimaManfaatChart;
use App\Filament\Widgets\PenerimaManfaatOverview;
use App\Filament\Widgets\RastraChart;
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->spa()
            ->maxContentWidth('full')
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->passwordReset()
            ->profile()
            ->plugins([
                QuickCreatePlugin::make(),
                FilamentSpatieLaravelBackupPlugin::make(),
                SpotlightPlugin::make(),
            ])
            ->colors([
                'primary' => Color::Teal,
            ])
            ->font('Poppins')
            ->favicon('images/logo2.png')
            ->darkMode(false)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->globalSearchKeyBindings(['CTRL+K'])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Master'),
                NavigationGroup::make()
                    ->label('Settings')
                    ->collapsible(),
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->widgets([
                AccountWidget::class,
                //                DashboardMultiWidget::class,
                //                Widgets\FilamentInfoWidget::class,
                PenerimaManfaatOverview::class,
                PenerimaManfaatChart::class,
                RastraChart::class,
                LatestPenerimaManfaat::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
