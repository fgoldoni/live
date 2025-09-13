<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Events\Models\Date;

trait HasManyDate
{
    public function dates(): HasMany
    {
        return $this->hasMany(Date::class);
    }
}
