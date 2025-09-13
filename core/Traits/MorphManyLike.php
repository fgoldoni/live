<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Likes\Models\Like;

trait MorphManyLike
{
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likable');
    }
}
