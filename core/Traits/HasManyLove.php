<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Loves\Models\Love;

trait HasManyLove
{
    public function loves(): HasMany
    {
        return $this->hasMany(Love::class);
    }
}
