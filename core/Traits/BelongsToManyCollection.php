<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Collections\Models\Collection;

trait BelongsToManyCollection
{
    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class);
    }
}
