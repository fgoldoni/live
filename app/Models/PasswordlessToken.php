<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasExtraUlid;
use Core\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

class PasswordlessToken extends Model
{
    use BelongsToUser;
    use HasExtraUlid;

    protected $fillable = [
        'ulid', 'user_id', 'token', 'expires_at', 'used_at', 'metadata',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
        'metadata'   => AsArrayObject::class,
    ];
}
