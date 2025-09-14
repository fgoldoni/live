<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Auth\MagicLinkGeneratorInterface;
use App\Services\Auth\SignedUrlMagicLinkGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MagicLinkGeneratorInterface::class, SignedUrlMagicLinkGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
