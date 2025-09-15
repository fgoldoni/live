<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasExtraUlid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\OneTimePasswords\Models\OneTimePassword as BaseOneTimePassword;

class OneTimePassword extends BaseOneTimePassword
{
    use SoftDeletes;
    use HasExtraUlid;
}
