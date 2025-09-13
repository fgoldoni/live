<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Videos\Models\Video;

trait MorphManyVideo
{
    public function videos(): MorphMany
    {
        return $this->morphMany(Video::class, 'videoable');
    }
}
