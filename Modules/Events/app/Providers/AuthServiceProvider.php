<?php

declare(strict_types=1);

namespace Modules\Events\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Events\Models\Event;
use Modules\Events\Policies\EventPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Event::class                => EventPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
