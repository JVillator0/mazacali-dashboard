<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Resources;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->maxContentWidth(MaxWidth::Full)
            ->login()
            ->passwordReset()
            ->profile(isSimple: false)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigation(fn (NavigationBuilder $builder) => $builder->groups($this->getMenuNavigation()))
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ])
            ->plugin(
                // https://filamentphp.com/plugins/bezhansalleh-shield
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),

                // https://filamentphp.com/plugins/jeffgreco-breezy
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        userMenuLabel: 'My Profile', // Customizes the 'account' link label in the panel User Menu (default = null)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        navigationGroup: 'Settings', // Sets the navigation group for the My Profile page (default = null)
                        hasAvatars: true, // Enables the avatar upload form component (default = false)
                        slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
                    ),

                //
            );
    }

    private function getMenuNavigation(): array
    {
        return [
            NavigationGroup::make('home')
                ->label(__('Home'))
                ->items([
                    NavigationItem::make('home')
                        ->label(__('Home'))
                        ->icon('heroicon-o-home')
                        ->url(Dashboard::getUrl()),
                ]),

            NavigationGroup::make('sales')
                ->label(__('Sales'))
                ->items([
                    ...Resources\OrderResource::getNavigationItems(),
                ]),

            NavigationGroup::make('restaurant')
                ->label(__('Restaurant management'))
                ->items([
                    ...Resources\ProductResource::getNavigationItems(),
                    ...Resources\ProductCategoryResource::getNavigationItems(),
                    ...Resources\ProductSubcategoryResource::getNavigationItems(),
                    ...Resources\TableResource::getNavigationItems(),
                ]),

            NavigationGroup::make('access_management')
                ->label(__('Access management'))
                ->items([
                    ...Resources\UserResource::getNavigationItems(),
                    ...Resources\RoleResource::getNavigationItems(),
                ]),
        ];
    }
}
