<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Auth\MagicLinkGenerator;
use App\Contracts\Auth\PhoneNormalizer;
use App\Contracts\Geo\CountryResolver;
use App\Services\Auth\LibPhoneNormalizer;
use App\Services\Auth\SignedUrlMagicLinkGenerator;
use App\Services\Geo\StevebaumanLocationCountryResolver;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

final class BindingServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(StatefulGuard::class, fn ($app) => $app['auth']->guard(
            $app['config']->get('auth.defaults.guard', 'web')
        ));

        $this->app->singleton(PhoneNormalizer::class, function ($app) {
            $fallback = (string) $app['config']->get('countries.default', 'DE');
            return new LibPhoneNormalizer($fallback);
        });

        $this->app->singleton(MagicLinkGenerator::class, function ($app) {
            $ttl = (int) $app['config']->get('auth.magic_link_ttl', 15);
            return new SignedUrlMagicLinkGenerator(
                $app->make(Mailer::class),
                $ttl
            );
        });

        $this->app->singleton(CountryResolver::class, fn () => new StevebaumanLocationCountryResolver());
    }

    public function provides(): array
    {
        return [
            PhoneNormalizer::class,
            MagicLinkGenerator::class,
            CountryResolver::class,
            StatefulGuard::class,
        ];
    }
}
