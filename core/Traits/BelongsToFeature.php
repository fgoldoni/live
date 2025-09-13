<?php

namespace Core\Traits;

use Modules\Events\Entities\Feature;

trait BelongsToFeature
{
    public function feature()
    {
        return $this->belongsTo(Feature::class)->withDefault();
    }
}
