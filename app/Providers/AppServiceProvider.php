<?php

declare(strict_types=1);

namespace App\Providers;

use Override;
use App\Policies\TeamPolicy;
use Goldoni\LaravelTeams\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
    }

    public function boot(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
        Model::automaticallyEagerLoadRelationships();
        Gate::policy(Team::class, TeamPolicy::class);
    }
}
