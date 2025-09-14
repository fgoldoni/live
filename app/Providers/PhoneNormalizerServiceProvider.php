<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Auth\LibPhoneNormalizer;
use App\Services\Auth\PhoneNormalizerInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

final class PhoneNormalizerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(PhoneNormalizerInterface::class, function ($app): PhoneNormalizerInterface {
            $fallback = (string) $app['config']->get('app.phone_default_country', 'DE');

            return new LibPhoneNormalizer($fallback);
        });
    }

    public function provides(): array
    {
        return [PhoneNormalizerInterface::class];
    }
}
