<?php

declare(strict_types=1);

namespace App\Providers;

use App\Policies\TeamPolicy;
use Goldoni\LaravelTeams\Models\Team;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Gate::policy(Team::class, TeamPolicy::class);
    }
}
