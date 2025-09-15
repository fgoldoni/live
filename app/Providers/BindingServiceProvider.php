<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Auth\MagicLinkGenerator;
use App\Contracts\Auth\PhoneNormalizer;
use App\Contracts\Geo\CountryResolver;
use App\Contracts\Notifications\WhatsAppClient as WhatsAppClientContract;
use App\Contracts\Otp\OtpManager as OtpManagerContract;
use App\Services\Auth\LibPhoneNormalizer;
use App\Services\Auth\SignedUrlMagicLinkGenerator;
use App\Services\Geo\StevebaumanLocationCountryResolver;
use App\Services\Notifications\MetaCloudClient;
use App\Services\Otp\DefaultOtpManager;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\ServiceProvider;
use LogicException;

final class BindingServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(WhatsAppClientContract::class, static fn (Container $container): MetaCloudClient => new MetaCloudClient($container->make(HttpFactory::class)));
        $this->app->singleton(OtpManagerContract::class, DefaultOtpManager::class);

        $this->app->bind(static function (Container $container): StatefulGuard {
            $authManager = $container->make('auth');
            $guard       = $authManager->guard(config('auth.defaults.guard', 'web'));

            if (! $guard instanceof StatefulGuard) {
                throw new LogicException('Default guard is not stateful.');
            }

            return $guard;
        });

        $this->app->singleton(static function (Container $container): PhoneNormalizer {
            $fallback = (string) config('countries.default', 'DE');

            return new LibPhoneNormalizer($fallback);
        });

        $this->app->singleton(static function (Container $container): MagicLinkGenerator {
            $ttl = (int) config('auth.magic_link_ttl', 15);

            return new SignedUrlMagicLinkGenerator(
                $container->make(Mailer::class),
                $ttl
            );
        });

        $this->app->singleton(CountryResolver::class, static fn (): CountryResolver => new StevebaumanLocationCountryResolver);
    }

    /**
     * @return array<int, class-string>
     */
    public function provides(): array
    {
        return [
            StatefulGuard::class,
            PhoneNormalizer::class,
            MagicLinkGenerator::class,
            CountryResolver::class,
            WhatsAppClientContract::class,
            OtpManagerContract::class,
        ];
    }
}
