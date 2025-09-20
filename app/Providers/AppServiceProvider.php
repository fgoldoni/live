<?php

declare(strict_types=1);

namespace App\Providers;

use Modules\Teams\Enums\LocaleEnum;
use Override;
use App\Policies\TeamPolicy;
use Goldoni\LaravelTeams\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\NovaTranslatable\Translatable;

use Illuminate\Support\Facades\Event;
use Laravel\Nova\Events\StartedImpersonating;
use Laravel\Nova\Events\StoppedImpersonating;

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
        Translatable::defaultLocales(['fr', 'en', 'de']);
        Gate::policy(Team::class, TeamPolicy::class);

        Event::listen(StartedImpersonating::class, function ($event) {
            logger("User {$event->impersonator->name} started impersonating {$event->impersonated->name}");
        });

        Event::listen(StoppedImpersonating::class, function ($event) {
            logger("User {$event->impersonator->name} stopped impersonating {$event->impersonated->name}");
        });
    }
}
