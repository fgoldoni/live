<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Events\Models\EventMc;
use Modules\Events\Models\Mc;

trait BelongsToManyMc
{
    public function mcs(): BelongsToMany
    {
        return $this->belongsToMany(Mc::class)
            ->using(EventMc::class)
            ->withPivot('id', 'position')
            ->withTimestamps();
    }
}
