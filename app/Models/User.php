<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasExtraUlid;
use App\Models\Concerns\HasRoleScopes;
use App\Policies\UserPolicy;
use Goldoni\LaravelTeams\Concerns\HasTeams;
use Goldoni\LaravelVirtualWallet\Traits\HasWallets;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Laravel\Nova\Auth\Impersonatable;
use libphonenumber\PhoneNumberUtil;
use Spatie\OneTimePasswords\Models\Concerns\HasOneTimePasswords;
use Spatie\Permission\Traits\HasRoles;
use Throwable;

#[UsePolicy(UserPolicy::class)]
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasExtraUlid;
    use SoftDeletes;
    use HasWallets;
    use HasRoleScopes;
    use HasOneTimePasswords;
    use HasTeams;
    use HasRoles;
    use Impersonatable;

    protected $fillable = [
        'name',
        'ulid',
        'email',
        'phone',
        'password',
        'current_team_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'phone_country_iso2',
        'is_african_phone',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed'
        ];
    }


    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function routeNotificationForVonage(Notification $notification): string
    {
        return (string) $this->phone;
    }

    public function routeNotificationForWhatsapp(Notification $notification): string
    {
        return (string) $this->phone;
    }

    /** @return Attribute<?string, never> */
    protected function phoneCountryIso2(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                $phone = $this->phone ?? '';

                if ($phone === '' || $phone[0] !== '+') {
                    return null;
                }

                $phoneNumberUtil = PhoneNumberUtil::getInstance();
                try {
                    $number = $phoneNumberUtil->parse($phone);

                    return strtoupper((string) $phoneNumberUtil->getRegionCodeForNumber($number));
                } catch (Throwable) {
                    return null;
                }
            }
        );
    }

    /** @return Attribute<?string, never> */
    protected function isAfricanPhone(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                /** @var string|null $iso2 */
                $iso2 = $this->getAttribute('phone_country_iso2');

                if (! $iso2) {
                    return false;
                }

                return in_array($iso2, config('countries.africa_iso2'), true);
            }
        );
    }

    public function canImpersonate(): bool
    {
        return $this->hasPermissionTo('impersonate');
    }


    public function canBeImpersonated(): bool
    {
        return !$this->trashed() && ($this->hasRole(config('model-permissions.roles.seller')) || $this->hasRole(config('model-permissions.roles.manager')));
    }
}
