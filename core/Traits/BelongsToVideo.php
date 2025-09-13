<?php

namespace Core\Traits;

use Modules\Events\Entities\Video;

trait BelongsToVideo
{
    public function video()
    {
        return $this->belongsTo(Video::class)->withDefault();
    }
}
