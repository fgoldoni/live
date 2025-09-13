<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Events\Models\Event;

trait HasManyEvent
{
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
