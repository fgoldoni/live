<?php

namespace Core\Traits;

use Modules\Events\Entities\Extra;

trait BelongsToExtra
{
    public function extra()
    {
        return $this->belongsTo(Extra::class)->withDefault();
    }
}
