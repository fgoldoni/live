<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Events\App\Models\Entry;

trait BelongsToManyEntry
{
    public function entries(): BelongsToMany
    {
        return $this->belongsToMany(Entry::class);
    }
}
