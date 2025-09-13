<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Events\Models\Event;

trait BelongsToManyEvent
{
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }
}
