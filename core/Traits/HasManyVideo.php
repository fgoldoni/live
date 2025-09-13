<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Models\Models\Video;

trait HasManyVideo
{
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
