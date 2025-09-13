<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Events\Models\EventSponsor;
use Modules\Events\Models\Sponsor;

trait BelongsToManySponsor
{
    public function sponsors(): BelongsToMany
    {
        return $this->belongsToMany(Sponsor::class)
            ->using(EventSponsor::class)
            ->withPivot('id', 'avatar', 'position')
            ->withTimestamps();
    }
}
