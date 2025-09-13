<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Events\Models\Event;

trait BelongsToEvent
{
    public function event(): belongsTo
    {
        return $this->belongsTo(Event::class)->withDefault([]);
    }
}
