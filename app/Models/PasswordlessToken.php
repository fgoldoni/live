<?php

declare(strict_types=1);

namespace App\Models;

use Core\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordlessToken extends Model
{
    use HasFactory;
    use BelongsToUser;

    protected $fillable = [
        'user_id', 'token', 'expires_at', 'used_at', 'metadata',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
        'metadata'   => 'array',
    ];
}
