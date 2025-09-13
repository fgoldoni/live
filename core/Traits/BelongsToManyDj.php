<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Events\Models\Dj;
use Modules\Events\Models\DjEvent;

trait BelongsToManyDj
{
    public function djs(): BelongsToMany
    {
        return $this->belongsToMany(Dj::class)
            ->using(DjEvent::class)
            ->withPivot('id', 'position')
            ->withTimestamps();
    }
}
