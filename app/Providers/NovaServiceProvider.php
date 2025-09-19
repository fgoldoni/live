<?php

declare(strict_types=1);

namespace App\Providers;

use Override;
use App\Models\User;
use App\Nova\Dashboards\Main;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Features;
use Laravel\Nova\Dashboard;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Laravel\Nova\Tool;
use Sereny\NovaPermissions\NovaPermissions;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    #[Override]
    public function boot(): void
    {
        parent::boot();

        Nova::withBreadcrumbs();


        Nova::footer(fn ($request) => Blade::render(
            <<<HTML
                <footer class="text-center text-gray-500 font-semibold">
                  © 2025 Sell First. All rights reserved.
                </footer>
            HTML
        ));
    }

    /**
     * Register the configurations for Laravel Fortify.
     */
    #[Override]
    protected function fortify(): void
    {
        Nova::fortify()
            ->features([
                Features::updatePasswords(),
                // Features::emailVerification(),
                // Features::twoFactorAuthentication(['confirm' => true, 'confirmPassword' => true]),
            ])
            ->register();
    }

    /**
     * Register the Nova routes.
     */
    #[Override]
    protected function routes(): void
    {
        Nova::routes()
            ->withoutAuthenticationRoutes()
            ->withoutPasswordResetRoutes()
            ->withoutEmailVerificationRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     */
    #[Override]
    protected function gate(): void
    {
        Gate::define('viewNova', fn (User $user): bool => $user->hasPermissionTo('nova'));
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array<int, Dashboard>
     */
    #[Override]
    protected function dashboards(): array
    {
        return [
            new Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array<int, Tool>
     */
    #[Override]
    public function tools(): array
    {
        return [
            (new NovaPermissions)->canSee(fn ($request) => $request->user()->isSuperAdmin()),
        ];
    }

    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        parent::register();

        //
    }
}
