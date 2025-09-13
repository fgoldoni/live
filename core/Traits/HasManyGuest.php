<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Events\Models\Guest;

trait HasManyGuest
{
    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }
}
