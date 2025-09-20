<?php

declare(strict_types=1);

namespace Modules\Events\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Events\Repositories\Contracts\EventsRepository;
use Modules\Events\Repositories\Eloquent\EloquentEventsRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    protected bool $defer = false;

    public function boot(): void
    {
        $this->app->bind(EventsRepository::class, EloquentEventsRepository::class);
    }
}
