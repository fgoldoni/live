<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasExtraUlid;
use App\Models\Concerns\HasRoleScopes;
use Goldoni\LaravelVirtualWallet\Traits\HasWallets;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Spatie\OneTimePasswords\Models\Concerns\HasOneTimePasswords;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasExtraUlid;
    use SoftDeletes;
    use HasWallets;
    use HasRoleScopes;
    use HasOneTimePasswords;

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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
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
}
